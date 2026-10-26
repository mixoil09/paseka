CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    weight VARCHAR(50),
    image VARCHAR(255),
    in_stock TINYINT(1) DEFAULT 1,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address TEXT NOT NULL,
    comment TEXT,
    total DECIMAL(10, 2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (name) VALUES
    ('Мёд'),
    ('Свечи и воск'),
    ('Подарочные наборы');

INSERT INTO products (name, description, price, weight, image, category_id) VALUES
    ('Мёд липовый', 'Натуральный липовый мёд с нашей пасеки.', 550.00, '0.5 кг', 'honey-lipa.jpg', 1),
    ('Мёд гречишный', 'Тёмный гречишный мёд, насыщенный вкус.', 600.00, '0.5 кг', 'honey-grechka.jpg', 1),
    ('Свеча восковая', 'Свеча из натурального воска.', 300.00, '1 шт', 'candle.jpg', 2),
    ('Подарочный набор', 'Мёд + свеча + соты.', 1200.00, '1.5 кг', 'gift.jpg', 3);