<?php
declare(strict_types=1);
require __DIR__ . '/../app/config.php';
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY id")->fetchAll();

$sql = "SELECT * FROM products WHERE in_stock = 1";
$params = [];
if ($categoryId) { $sql .= " AND category_id = :cat"; $params['cat'] = $categoryId; }
$sql .= " ORDER BY id";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$products = $stmt->fetchAll();

$pageTitle = 'Каталог — Пасека';
include __DIR__ . '/../app/includes/header.php';
?>
<section class="catalog-page">
    <h1>Каталог</h1>
    <nav class="categories">
        <a href="/catalog.php" class="<?= $categoryId === null ? 'active' : '' ?>">Все</a>
        <?php foreach ($categories as $cat): ?>
            <a href="/catalog.php?category=<?= (int)$cat['id'] ?>" class="<?= $categoryId === (int)$cat['id'] ? 'active' : '' ?>"><?= htmlspecialchars($cat['name']) ?></a>
        <?php endforeach; ?>
    </nav>
    <div class="honeycomb-grid">
        <?php foreach ($products as $p): ?>
            <a href="/product.php?id=<?= (int)$p['id'] ?>" class="comb-item">
                <div class="comb-item__inner">
                    <img src="/img/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                    <div class="comb-item__info">
                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        <p class="price"><?= number_format((float)$p['price'], 0, '.', ' ') ?> ₽</p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/../app/includes/footer.php'; ?>
