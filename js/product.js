document.addEventListener('DOMContentLoaded', () => {
    const layout = document.getElementById('product-layout');
    if (!layout) return;

    const id = parseInt(new URLSearchParams(window.location.search).get('id'));

    fetch('data/products.json')
        .then(res => res.json())
        .then(products => {
            const p = products.find(item => item.id === id);
            if (!p) { layout.innerHTML = '<h2 style="color:#fff;">Товар не найден</h2>'; return; }

            layout.innerHTML = `
                <div class="product-visual">
                    <div class="product-visual__frame">
                        <img class="product-visual__img" src="img/${p.image}" alt="${p.name}">
                    </div>
                </div>
                <div class="product-details">
                    <h1 class="product-details__title">${p.name}</h1>
                    <div class="product-details__price">${p.price} ₽</div>
                    <div class="product-details__desc">
                        <p>${p.description}</p>
                        <p>Вес: ${p.weight}</p>
                    </div>
                    <button class="btn-add btn-add--large" onclick="addToCart(${p.id}, '${p.name}', ${p.price})">Добавить в корзину</button>
                    <a href="catalog.html" class="back-link">← Вернуться в каталог</a>
                </div>
            `;
        });
});
