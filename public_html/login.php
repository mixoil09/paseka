<?php $pageTitle = 'Вход — Пасека'; include __DIR__ . '/../app/includes/header.php'; ?>
<section class="section" style="max-width: 400px; margin: 60px auto;">
    <h1 style="text-align:center; margin-bottom: 30px;">Вход / Регистрация</h1>
    <p id="auth-msg" style="text-align:center; margin-bottom: 20px; font-weight: bold;"></p>
    <form id="login-form" class="order-form" style="max-width: 100%;">
        <label>Email <input type="email" id="login-email" required></label>
        <label>Пароль <input type="password" id="login-pass" required></label>
        <button type="submit" class="btn-add">Войти</button>
    </form>
    <hr style="border-color: var(--border); margin: 30px 0;">
    <form id="register-form" class="order-form" style="max-width: 100%;">
        <label>Имя <input type="text" id="reg-name" required></label>
        <label>Email <input type="email" id="reg-email" required></label>
        <label>Пароль <input type="password" id="reg-pass" required></label>
        <button type="submit" class="btn-add" style="background: var(--surface-2); color: var(--primary);">Зарегистрироваться</button>
    </form>
</section>
<script src="/js/auth.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const msg = document.getElementById('auth-msg');
    document.getElementById('login-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const res = auth.login(document.getElementById('login-email').value, document.getElementById('login-pass').value);
        if (res.success) window.location.href = res.role === 'admin' ? '/admin.html' : '/profile.php';
        else { msg.textContent = res.error; msg.style.color = '#ff6b6b'; }
    });
    document.getElementById('register-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const res = auth.register(document.getElementById('reg-name').value, document.getElementById('reg-email').value, document.getElementById('reg-pass').value);
        if (res.success) { msg.textContent = 'Успешно! Теперь можете войти.'; msg.style.color = '#4ade80'; }
        else { msg.textContent = res.error; msg.style.color = '#ff6b6b'; }
    });
});
</script>
<?php include __DIR__ . '/../app/includes/footer.php'; ?>
