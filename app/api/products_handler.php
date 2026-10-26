<?php
declare(strict_types=1);

require __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

$sql = "SELECT p.id, p.name, p.description, p.price, p.weight, p.image, c.name AS category
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.in_stock = 1";

$params = [];

if ($categoryId !== null && $categoryId > 0) {
    $sql .= " AND p.category_id = :cat";
    $params['cat'] = $categoryId;
}

$sql .= " ORDER BY p.id";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

foreach ($products as &$p) {
    $p['id']    = (int)$p['id'];
    $p['price'] = (float)$p['price'];
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);