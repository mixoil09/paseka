<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Пасека', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/tilted-card.css">
    <link rel="stylesheet" href="/css/tilted-card-extras.css">
</head>
<body>
<header class="site-header">
    <a href="/" class="logo">🐝 Пасека</a>
    <nav class="site-nav">
        <a href="/">Главная</a>
        <a href="/catalog.php">Каталог</a>
        <a href="/about.php">О пасеке</a>
        <a href="/cart.php">Корзина (<span id="cart-count">0</span>)</a>
    </nav>
</header>
<main class="container">