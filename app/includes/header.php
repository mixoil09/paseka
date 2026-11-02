<?php
declare(strict_types=1);
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$isHome = ($uri === '/' || str_contains($uri, '/index.php'));
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Пасека', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <?php if ($isHome): ?>
        <link rel="stylesheet" href="/css/hive.css">
    <?php endif; ?>
</head>
<body class="<?= $isHome ? 'hive-theme' : '' ?>">
<header class="site-header">
    <a href="/" class="logo">🐝 Пасека</a>
    <nav class="site-nav">
        <a href="/">Главная</a>
        <a href="/catalog.php">Каталог</a>
        <a href="/about.php">О пасеке</a>
        <a href="/cart.php">Корзина<span id="cart-count">0</span></a>
        <span id="auth-nav" style="display:inline-flex; gap:4px; align-items:center;"></span>
    </nav>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const authNav = document.getElementById('auth-nav');
        if (!authNav) return;
        const user = JSON.parse(localStorage.getItem('current_user') || 'null');
        if (user) {
            if (user.role === 'admin') {
                authNav.innerHTML = `<a href="/admin.html" style="color: var(--primary); font-weight: bold;">⚙️ Админ-панель</a><a href="#" id="logout-btn" style="color: #ff6b6b;">Выход</a>`;
            } else {
                authNav.innerHTML = `<a href="/profile.php" style="color: var(--primary);">👤 ${user.name}</a><a href="#" id="logout-btn" style="color: #ff6b6b;">Выход</a>`;
            }
            document.getElementById('logout-btn').addEventListener('click', (e) => {
                e.preventDefault(); localStorage.removeItem('current_user'); window.location.href = '/';
            });
        } else {
            authNav.innerHTML = `<a href="/login.php">Вход</a>`;
        }
    });
    </script>
</header>
<main class="container">
