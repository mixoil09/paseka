<?php
declare(strict_types=1);
require __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$name = trim($input['name'] ?? '');
$phone = trim($input['phone'] ?? '');
$address = trim($input['address'] ?? '');
$comment = trim($input['comment'] ?? '');
$items = $input['items'] ?? [];

if (!$name || !$phone || !$address || empty($items)) {
    http_response_code(400); echo json_encode(['error' => 'Заполните все поля']); exit;
}

$total = 0.0;
$prepared = [];

foreach ($items as $item) {
    $pid = (int)($item['id'] ?? 0);
    $qty = (int)($item['quantity'] ?? 0);
    if ($pid <= 0 || $qty <= 0) continue;

    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = :id AND in_stock = 1");
    $stmt->execute(['id' => $pid]);
    $product = $stmt->fetch();
    
    if ($product) {
        $price = (float)$product['price'];
        $total += $price * $qty;
        $prepared[] = ['id' => $pid, 'quantity' => $qty, 'price' => $price];
    }
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, phone, address, comment, total) VALUES (:n, :p, :a, :c, :t)");
    $stmt->execute(['n'=>$name, 'p'=>$phone, 'a'=>$address, 'c'=>$comment, 't'=>$total]);
    $orderId = (int)$pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:oid, :pid, :qty, :price)");
    foreach ($prepared as $item) {
        $stmt->execute(['oid'=>$orderId, 'pid'=>$item['id'], 'qty'=>$item['quantity'], 'price'=>$item['price']]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'order_id' => $orderId]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500); echo json_encode(['error' => 'Ошибка сохранения']);
}
