<?php declare(strict_types=1); $isHome = ($_SERVER['REQUEST_URI'] === '/' || str_contains($_SERVER['REQUEST_URI'], '/index.php')); ?>
</main>
<footer class="site-footer">
    © <?= date('Y') ?> Пасека. Все права защищены.
</footer>
<script src="/js/main.js"></script>
<?php if ($isHome): ?>
    <script src="/js/hive.js"></script>
<?php endif; ?>
</body>
</html>
