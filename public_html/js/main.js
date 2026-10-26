'use strict';

// Логика для страницы корзины (cart.php)
function renderCart() {
    const cartItemsEl = document.getElementById('cart-items');
    if (!cartItemsEl) return; // не на странице корзины

    const cart = getCart();
    const emptyEl = document.getElementById('cart-empty');
    const contentEl = document.getElementById('cart-content');

    if (cart.length === 0) {
        emptyEl.classList.remove('hidden');
        contentEl.classList.add('hidden');
        return;
    }

    emptyEl.classList.add('hidden');
    contentEl.classList.remove('hidden');

    let total = 0;
    cartItemsEl.innerHTML = '';

    cart.forEach(item => {
        const sum = item.price * item.quantity;
        total += sum;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${escapeHtml(item.name)}</td>
            <td>${item.price} ₽</td>
            <td>
                <div class="cart-qty">
                    <button type="button" data-action="dec" data-id="${item.id}">−</button>
                    <input type="number" min="1" value="${item.quantity}" data-id="${item.id}">
                    <button type="button" data-action="inc" data-id="${item.id}">+</button>
                </div>
            </td>
            <td>${sum} ₽</td>
            <td><button type="button" class="remove-btn" data-action="remove" data-id="${item.id}">Удалить</button></td>
        `;
        cartItemsEl.appendChild(tr);
    });

    document.getElementById('cart-total').textContent = total.toLocaleString('ru-RU') + ' ₽';

    // Обработчики
    cartItemsEl.querySelectorAll('button[data-action]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.dataset.id, 10);
            const action = btn.dataset.action;
            if (action === 'inc') changeQuantity(id, 1);
            else if (action === 'dec') changeQuantity(id, -1);
            else if (action === 'remove') removeFromCart(id);
        });
    });

    cartItemsEl.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('change', () => {
            const id = parseInt(input.dataset.id, 10);
            const qty = parseInt(input.value, 10) || 1;
            const cart = getCart();
            const item = cart.find(i => i.id === id);
            if (item) {
                item.quantity = Math.max(1, qty);
                saveCart(cart);
                renderCart();
            }
        });
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Оформление заказа
function setupOrderForm() {
    const form = document.getElementById('order-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const cart = getCart();
        if (cart.length === 0) {
            showMessage('Корзина пуста', 'error');
            return;
        }

        const formData = new FormData(form);
        const data = {
            name: formData.get('name').trim(),
            phone: formData.get('phone').trim(),
            address: formData.get('address').trim(),
            comment: formData.get('comment').trim(),
            items: cart.map(item => ({ id: item.id, quantity: item.quantity }))
        };

        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Отправка...';

        try {
            const response = await fetch('/api/order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.error || 'Ошибка при оформлении заказа');
            }

            showMessage(`Заказ №${result.order_id} успешно оформлен!`, 'success');
            clearCart();
            renderCart();
            form.reset();
        } catch (err) {
            showMessage(err.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Оформить заказ';
        }
    });
}

function showMessage(text, type) {
    const el = document.getElementById('order-message');
    if (!el) return;
    el.textContent = text;
    el.className = type;
}

document.addEventListener('DOMContentLoaded', () => {
    renderCart();
    setupOrderForm();
});