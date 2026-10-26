<?php
declare(strict_types=1);

require __DIR__ . '/../app/config.php';

$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY id")->fetchAll();

$pageTitle = 'Каталог — Пасека';
include __DIR__ . '/../app/includes/header.php';
?>

<h1>Каталог</h1>

<nav class="categories">
    <a href="/catalog.php" class="<?= $categoryId === null ? 'active' : '' ?>">Все</a>
    <?php foreach ($categories as $cat): ?>
        <a href="/catalog.php?category=<?= (int)$cat['id'] ?>"
           class="<?= $categoryId === (int)$cat['id'] ? 'active' : '' ?>">
            <?= htmlspecialchars((string)$cat['name'], ENT_QUOTES, 'UTF-8') ?>
        </a>
    <?php endforeach; ?>
</nav>

<!-- React-остров: сюда рендерится каталог -->
<div id="catalog-root" data-category="<?= $categoryId ?? '' ?>"></div>

<?php include __DIR__ . '/../app/includes/footer.php'; ?>