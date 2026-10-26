<?php declare(strict_types=1); ?>
</main>
<footer class="site-footer">
    © <?= date('Y') ?> Пасека. Все права защищены.
</footer>

<!-- React 18 -->
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<!-- Babel — транспилирует JSX на лету -->
<script src="https://unpkg.com/@babel/standalone@7/babel.min.js"></script>

<!-- Твои компоненты -->
<script type="text/babel" src="/js/react/TiltedCard.jsx"></script>
<script type="text/babel" src="/js/react/catalog.jsx"></script>
<script src="/js/main.js"></script>
</body>
</html>