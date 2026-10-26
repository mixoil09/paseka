<?php
declare(strict_types=1);

require __DIR__ . '/../app/config.php';

$stmt = $pdo->query(
    "SELECT id, name, description, price, weight, image
     FROM products
     WHERE in_stock = 1
     ORDER BY id
     LIMIT 6"
);
$products = $stmt->fetchAll();

$pageTitle = 'Главная — Пасека';
include __DIR__ . '/../app/includes/header.php';
?>

<section class="hero">
    <h1>Натуральный мёд с нашей пасеки</h1>
    <p>Мёд, свечи, прополис — всё своё, всё натуральное.</p>
    <a href="/catalog.php" class="btn">Смотреть каталог</a>
</section>

<h2>Популярное</h2>
<div class="products-grid">
    <?php foreach ($products as $p): ?>
        <article class="product-card">
            <img src="/img/<?= htmlspecialchars((string)($p['image'] ?: 'placeholder.jpg'), ENT_QUOTES, 'UTF-8') ?>"
                 alt="<?= htmlspecialchars((string)$p['name'], ENT_QUOTES, 'UTF-8') ?>">
            <h3><?= htmlspecialchars((string)$p['name'], ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="desc"><?= htmlspecialchars((string)$p['description'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="price"><?= number_format((float)$p['price'], 0, '.', ' ') ?> ₽</p>
            <button class="btn" onclick='addToCart(<?= (int)$p['id'] ?>, <?= json_encode($p['name'], JSON_UNESCAPED_UNICODE) ?>, <?= (float)$p['price'] ?>)'>
                В корзину
            </button>
        </article>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../app/includes/footer.php'; ?>