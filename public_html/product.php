<?php
declare(strict_types=1);
require __DIR__ . '/../app/config.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id AND in_stock = 1");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();
if (!$product) { header('Location: /catalog.php'); exit; }
$pageTitle = $product['name'] . ' — Пасека';
include __DIR__ . '/../app/includes/header.php';
?>
<section class="product-page">
    <div class="product-layout">
        <div class="product-visual">
            <div class="product-visual__frame">
                <img class="product-visual__img" src="/img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
        </div>
        <div class="product-details">
            <h1 class="product-details__title"><?= htmlspecialchars($product['name']) ?></h1>
            <div class="product-details__price"><?= number_format((float)$product['price'], 0, '.', ' ') ?> ₽</div>
            <div class="product-details__desc">
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                <?php if ($product['weight']): ?><p class="product-details__weight">Вес: <?= htmlspecialchars($product['weight']) ?></p><?php endif; ?>
            </div>
            <button class="btn-add btn-add--large" onclick="addToCart(<?= (int)$product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>', <?= (float)$product['price'] ?>)">Добавить в корзину</button>
            <a href="/catalog.php" class="back-link">← Вернуться в каталог</a>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../app/includes/footer.php'; ?>
