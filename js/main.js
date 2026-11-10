'use strict';

const CART_KEY = 'paseka_cart';

// --- Управление корзиной ---
function getCart() {
    try { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); } 
    catch (e) { return []; }
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateCartCount();
}

function updateCartCount() {
    const el = document.getElementById('cart-count');
    if (el) {
        const total = getCart().reduce((sum, item) => sum + item.quantity, 0);
        el.textContent = total;
    }
}

function addToCart(id, name, price) {
    const cart = getCart();
    const existing = cart.find(item => item.id === id);
    
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ id, name, price, quantity: 1 });
    }
    
    saveCart(cart);
    showToast(`«${name}» добавлен в корзину`);
}

function removeFromCart(id) {
    const cart = getCart().filter(item => item.id !== id);
    saveCart(cart);
    if (typeof renderCart === 'function') renderCart();
}

function changeQuantity(id, delta) {
    const cart = getCart();
    const item = cart.find(i => i.id === id);
    if (!item) return;
    
    item.quantity += delta;
    if (item.quantity <= 0) {
        removeFromCart(id);
        return;
    }
    
    saveCart(cart);
    if (typeof renderCart === 'function') renderCart();
}

function clearCart() {
    localStorage.removeItem(CART_KEY);
    updateCartCount();
}

// --- Всплывающее уведомление ---
function showToast(message) {
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.cssText = 'position:fixed;bottom:20px;left:50%;transform:translateX(-50%);background:#222;color:#fff;padding:12px 20px;border-radius:8px;z-index:9999;opacity:0;transition:opacity 0.3s;text-align:center;white-space:nowrap;max-width:90%;';
    document.body.appendChild(toast);
    
    requestAnimationFrame(() => { toast.style.opacity = '1'; });
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

// --- Отрисовка страницы корзины ---
function renderCart() {
    const cartItemsEl = document.getElementById('cart-items');
    if (!cartItemsEl) return; // Мы не на странице корзины

    const cart = getCart();
    const emptyEl = document.getElementById('cart-empty');
    const contentEl = document.getElementById('cart-content');

    if (cart.length === 0) {
        if (emptyEl) emptyEl.classList.remove('hidden');
        if (contentEl) contentEl.classList.add('hidden');
        return;
    }

    if (emptyEl) emptyEl.classList.add('hidden');
    if (contentEl) contentEl.classList.remove('hidden');

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

    // Обработчики кнопок + и -
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

// --- Оформление заказа (Сохранение в localStorage) ---
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
        const phoneValue = formData.get('phone').trim();

        // Проверка телефона
        const phoneRegex = /^[\d\s\+\-\(\)]{10,20}$/;
        if (!phoneRegex.test(phoneValue)) {
            showMessage('Введите корректный номер телефона', 'error');
            return;
        }

        const data = {
            name: formData.get('name').trim(),
            phone: phoneValue,
            address: formData.get('address').trim(),
            comment: formData.get('comment').trim()
        };

        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Отправка...';

        try {
            // Имитация задержки сети (для реализма)
            await new Promise(resolve => setTimeout(resolve, 500));

            let orders = JSON.parse(localStorage.getItem('paseka_orders') || '[]');
            const newOrderId = orders.length > 0 ? Math.max(...orders.map(o => o.id)) + 1 : 1001;
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            const newOrder = {
                id: newOrderId,
                customer_name: data.name,
                phone: data.phone,
                address: data.address,
                comment: data.comment,
                total: total,
                status: 'new',
                created_at: new Date().toISOString(),
                items: cart.map(item => ({ id: item.id, quantity: item.quantity }))
            };

            // Сохраняем заказ в "базу" (localStorage)
            orders.push(newOrder);
            localStorage.setItem('paseka_orders', JSON.stringify(orders));

            // Запоминаем ID заказа для личного кабинета
            let myOrders = JSON.parse(localStorage.getItem('my_orders') || '[]');
            myOrders.push(newOrderId);
            localStorage.setItem('my_orders', JSON.stringify(myOrders));

            showMessage(`Заказ №${newOrderId} успешно оформлен!`, 'success');
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

// Запуск при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    renderCart();
    setupOrderForm();
});
