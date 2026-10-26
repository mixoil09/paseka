<?php
declare(strict_types=1);

require __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не разрешён']);
    exit;
}

$raw = file_get_contents('php://input');
$input = json_decode($raw ?: '[]', true);

if (!is_array($input) || $input === []) {
    http_response_code(400);
    echo json_encode(['error' => 'Пустой запрос']);
    exit;
}

$name    = trim((string)($input['name'] ?? ''));
$phone   = trim((string)($input['phone'] ?? ''));
$address = trim((string)($input['address'] ?? ''));
$comment = trim((string)($input['comment'] ?? ''));
$items   = is_array($input['items'] ?? null) ? $input['items'] : [];

if ($name === '' || $phone === '' || $address === '' || $items === []) {
    http_response_code(400);
    echo json_encode(['error' => 'Заполните все поля']);
    exit;
}

// Считаем сумму на сервере
$total    = 0.0;
$prepared = [];

foreach ($items as $item) {
    if (!is_array($item)) {
        continue;
    }

    $pid = (int)($item['id'] ?? 0);
    $qty = (int)($item['quantity'] ?? 0);

    if ($pid <= 0 || $qty <= 0 || $qty > 1000) {
        continue;
    }

    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = :id AND in_stock = 1");
    $stmt->execute(['id' => $pid]);
    $product = $stmt->fetch();

    if ($product === false) {
        continue;
    }

    $price   = (float)$product['price'];
    $total  += $price * $qty;
    $prepared[] = ['id' => $pid, 'quantity' => $qty, 'price' => $price];
}

if ($prepared === []) {
    http_response_code(400);
    echo json_encode(['error' => 'Некорректный состав заказа']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "INSERT INTO orders (customer_name, phone, address, comment, total)
         VALUES (:name, :phone, :address, :comment, :total)"
    );
    $stmt->execute([
        'name'    => $name,
        'phone'   => $phone,
        'address' => $address,
        'comment' => $comment,
        'total'   => $total,
    ]);

    $orderId = (int)$pdo->lastInsertId();

    $stmt = $pdo->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES (:oid, :pid, :qty, :price)"
    );

    foreach ($prepared as $item) {
        $stmt->execute([
            'oid'   => $orderId,
            'pid'   => $item['id'],
            'qty'   => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'order_id' => $orderId]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Order error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Не удалось сохранить заказ']);
}