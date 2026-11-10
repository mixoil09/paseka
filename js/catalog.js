'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('catalog-grid');
    if (!grid) return;

    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category');

    // Подсветка активной кнопки
    document.querySelectorAll('.categories a').forEach(link => {
        link.classList.remove('active');
        if (categoryId && link.getAttribute('href').includes(`category=${categoryId}`)) {
            link.classList.add('active');
        } else if (!categoryId && link.id === 'cat-all') {
            link.classList.add('active');
        }
    });

    fetch('data/products.json')
        .then(res => res.json())
        .then(products => {
            const filtered = categoryId 
                ? products.filter(p => p.category_id == categoryId) 
                : products;

            if (filtered.length === 0) {
                grid.innerHTML = '<p style="color:white; text-align:center; width:100%;">В этой категории пока нет товаров.</p>';
                return;
            }

            let html = '';
            let i = 0;
            let rowIndex = 0;

            // ГРУППИРУЕМ В РЯДЫ: 4 элемента, затем 3, затем 4...
            while (i < filtered.length) {
                let count = (rowIndex % 2 === 0) ? 4 : 3;
                let rowItems = filtered.slice(i, i + count);
                
                if (rowItems.length > 0) {
                    let rowClass = (rowIndex % 2 === 0) ? 'hive-row' : 'hive-row hive-row--offset';
                    html += `<div class="${rowClass}">` + renderRow(rowItems) + `</div>`;
                }
                
                i += count;
                rowIndex++;
            }

            grid.innerHTML = html;
        })
        .catch(err => console.error('Ошибка:', err));

    function renderRow(items) {
        return items.map(p => `
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
    }
});
