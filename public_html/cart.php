<?php
declare(strict_types=1);

$pageTitle = 'Корзина — Пасека';
include __DIR__ . '/../app/includes/header.php';
?>

<h1>Корзина</h1>

<div id="cart-empty" class="hidden">
    <p>Корзина пуста. <a href="/catalog.php">Перейти в каталог</a></p>
</div>

<div id="cart-content" class="hidden">
    <table class="cart-table">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Сумма</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="cart-items"></tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="right"><strong>Итого:</strong></td>
                <td colspan="2"><strong id="cart-total">0 ₽</strong></td>
            </tr>
        </tfoot>
    </table>

    <h2>Оформление заказа</h2>
    <form id="order-form" class="order-form">
        <label>Имя *
            <input type="text" name="name" required maxlength="100">
        </label>
        <label>Телефон *
            <input type="tel" name="phone" required maxlength="30" placeholder="+7 (___) ___-__-__">
        </label>
        <label>Адрес доставки *
            <textarea name="address" required rows="3" maxlength="500"></textarea>
        </label>
        <label>Комментарий
            <textarea name="comment" rows="2" maxlength="500"></textarea>
        </label>
        <button type="submit" class="btn">Оформить заказ</button>
        <p id="order-message"></p>
    </form>
</div>

<?php include __DIR__ . '/../app/includes/footer.php'; ?>