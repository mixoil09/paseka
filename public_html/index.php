<?php
declare(strict_types=1);
require __DIR__ . '/../app/config.php';
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY id")->fetchAll();
$pageTitle = 'Пасека — натуральный мёд';
include __DIR__ . '/../app/includes/header.php';
?>

<section class="hive-hero" id="hive-hero">
    <div class="hive-video" id="hive-video">
        <video id="hive-video-el" class="hive-video__el" autoplay muted playsinline poster="/img/hive-outside.jpg">
            <source src="/img/hive-intro.mp4" type="video/mp4">
        </video>
        <div class="hive-video__vignette"></div>
        <button class="hive-skip" id="hive-skip" type="button">Пропустить →</button>
        <label class="hive-video__dontshow">
            <input type="checkbox" id="hive-dontshow" checked> <span>Больше не показывать</span>
        </label>
    </div>

    <div class="hive-scene" id="hive-inside">
        <div class="hive-scene__bg" style="background-image: url('/img/hive-inside.jpg');"></div>
        <div class="hive-scene__vignette"></div>
        <div class="hive-content">
            <h1 class="hive-title">Что ищем в улье?</h1>
            <p class="hive-subtitle">Выберите категорию — и пчела покажет дорогу</p>
            <div class="honeycomb-menu">
                <a href="/catalog.php" class="hex"><span class="face">Все</span></a>
                <?php foreach ($categories as $cat): ?>
                    <a href="/catalog.php?category=<?= (int)$cat['id'] ?>" class="hex">
                        <span class="face"><?= htmlspecialchars($cat['name']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../app/includes/footer.php'; ?>
