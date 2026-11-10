'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('catalog-grid');
    if (!grid) {
        console.error("Контейнер catalog-grid не найден на странице!");
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category');

    // Подсветка активной категории
    document.querySelectorAll('.categories a').forEach(link => {
        link.classList.remove('active');
        if (categoryId && link.getAttribute('href').includes(`category=${categoryId}`)) {
            link.classList.add('active');
        } else if (!categoryId && link.id === 'cat-all') {
            link.classList.add('active');
        }
    });

    // Загрузка данных из JSON (наша имитация базы данных)
    fetch('data/products.json')
        .then(res => {
            if (!res.ok) throw new Error('Ошибка загрузки JSON (404)');
            return res.json();
        })
        .then(products => {
            const filtered = categoryId 
                ? products.filter(p => p.category_id == categoryId) 
                : products;

            if (filtered.length === 0) {
                grid.innerHTML = '<p style="color:white; text-align:center; width:100%;">В этой категории пока нет товаров.</p>';
                return;
            }

            grid.innerHTML = filtered.map(p => `
                <a href="product.html?id=${p.id}" class="comb-item">
                    <div class="comb-item__inner">
                        <img src="img/${p.image}" alt="${p.name}">
                        <div class="comb-item__info">
                            <h3>${p.name}</h3>
                            <p class="price">${p.price} ₽</p>
                        </div>
                    </div>
                </a>
            `).join('');
        })
        .catch(err => {
            console.error('Ошибка:', err);
            grid.innerHTML = '<p style="color:red; text-align:center; width:100%;">Ошибка загрузки товаров. Проверьте консоль.</p>';
        });
});
