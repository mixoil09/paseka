<?php
declare(strict_types=1);
require __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $orderId = (int)($input['order_id'] ?? 0);
    $status = $input['status'] ?? '';
    
    if ($orderId > 0 && in_array($status, ['approved', 'rejected'])) {
        $stmt = $pdo->prepare("UPDATE orders SET status = :s WHERE id = :id");
        $stmt->execute(['s'=>$status, 'id'=>$orderId]);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400); echo json_encode(['error' => 'Неверные данные']);
    }
    exit;
}

$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();
foreach ($orders as &$o) {
    $o['total'] = (float)$o['total'];
    $o['status'] = $o['status'] ?? 'new';
}
echo json_encode($orders, JSON_UNESCAPED_UNICODE);
