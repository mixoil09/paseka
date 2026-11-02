<?php $pageTitle = 'Личный кабинет — Пасека'; include __DIR__ . '/../app/includes/header.php'; ?>
<section class="section" style="max-width: 800px; margin: 0 auto;">
    <h1>Личный кабинет</h1>
    <div id="profile-content"><p>Загрузка...</p></div>
</section>
<script src="/js/auth.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const user = auth.getCurrentUser();
    if (!user) { window.location.href = '/login.php'; return; }
    const content = document.getElementById('profile-content');
    const myOrderIds = JSON.parse(localStorage.getItem('my_orders') || '[]');
    let ordersHtml = '<p style="color:var(--text-muted)">Вы пока не сделали ни одного заказа.</p>';
    
    if (myOrderIds.length > 0) {
        try {
            const res = await fetch('/api/admin_orders.php');
            const allOrders = await res.json();
            const myOrders = allOrders.filter(o => myOrderIds.includes(o.id));
            if (myOrders.length > 0) {
                ordersHtml = `<table class="cart-table" style="margin-top: 20px;"><thead><tr><th>№</th><th>Дата</th><th>Сумма</th><th>Статус</th></tr></thead><tbody>
                ${myOrders.map(o => `<tr><td>#${o.id}</td><td>${new Date(o.created_at).toLocaleDateString('ru-RU')}</td><td>${o.total} ₽</td><td><span style="color: ${o.status === 'approved' ? '#4ade80' : (o.status === 'rejected' ? '#ff6b6b' : '#f5b700')}">${o.status === 'approved' ? 'Одобрен' : (o.status === 'rejected' ? 'Отклонен' : 'В обработке')}</span></td></tr>`).join('')}
                </tbody></table>`;
            }
        } catch (e) {}
    }
    content.innerHTML = `<p style="font-size:1.2rem; margin-bottom:20px;">Привет, <strong style="color:var(--primary)">${user.name}</strong>!</p><h2 style="margin-bottom:20px;">Мои заказы</h2>${ordersHtml}`;
});
</script>
<?php include __DIR__ . '/../app/includes/footer.php'; ?>
