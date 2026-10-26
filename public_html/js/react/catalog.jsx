const { useEffect, useState } = React;

// ---- Карточка одного товара ----
function HoneyCard({ product, onAdd }) {
    const back = (
        <div className="honey-card-back">
            <h3>{product.name}</h3>
            <p>{product.description}</p>
            {product.weight && <p className="weight">{product.weight}</p>}
            <p className="hint">Нажмите ещё раз, чтобы вернуться</p>
        </div>
    );

    const overlay = (
        <div className="honey-card-overlay">
            <h3>{product.name}</h3>
            <p className="price">
                {Number(product.price).toLocaleString('ru-RU')} ₽
            </p>
            <button
                className="btn"
                onClick={(e) => {
                    e.stopPropagation();   // не переворачивать карточку при клике на кнопку
                    onAdd(product);
                }}
            >
                В корзину
            </button>
        </div>
    );

    return (
        <window.TiltedCard
            imageSrc={`/img/${product.image || 'placeholder.jpg'}`}
            altText={product.name}
            captionText={product.name}
            containerWidth="300px"
            containerHeight="360px"
            imageWidth="300px"
            imageHeight="300px"
            scaleOnHover={1.06}
            rotateAmplitude={14}
            backContent={back}
            overlayContent={overlay}
            displayOverlayContent={true}
            radius={20}
            glareColor="#ffd700"
            glare={0.6}
            shadow={0.45}
            smoothness={0.55}
            flipOnClick={true}
        />
    );
}

// ---- Каталог: загружает товары и рендерит ----
function CatalogApp({ categoryId }) {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        setLoading(true);
        const url = categoryId
            ? `/api/products.php?category=${categoryId}`
            : '/api/products.php';

        fetch(url)
            .then((r) => {
                if (!r.ok) throw new Error('Не удалось загрузить товары');
                return r.json();
            })
            .then(setProducts)
            .catch((e) => setError(e.message))
            .finally(() => setLoading(false));
    }, [categoryId]);

    const handleAdd = (product) => {
        // Используем существующий addToCart из main.js
        window.addToCart(product.id, product.name, product.price);
    };

    if (loading) return <p>Загрузка...</p>;
    if (error) return <p className="error">Ошибка: {error}</p>;
    if (products.length === 0) return <p>Товаров пока нет</p>;

    return (
        <div className="tilted-grid">
            {products.map((p) => (
                <HoneyCard key={p.id} product={p} onAdd={handleAdd} />
            ))}
        </div>
    );
}

// ---- Монтирование ----
document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('catalog-root');
    if (!root) return;

    const categoryId = root.dataset.category ? parseInt(root.dataset.category, 10) : null;
    ReactDOM.createRoot(root).render(<CatalogApp categoryId={categoryId} />);
});