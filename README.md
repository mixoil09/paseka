# Пасека — интернет-магазин продуктов пчеловодства

## Стек

- **PHP 8.2**
- **MySQL 8** (через PDO)
- **HTML / CSS / JavaScript** (vanilla, без фреймворков)
- **localStorage** для корзины на клиенте

## Структура

paseka/

├── .gitignore

├── README.md

├── app/                     # Серверная часть (вне public_html)

│   ├── .env.example

│   ├── config.php

│   ├── api/

│   │   ├── admin_orders.php

│   │   ├── order_handler.php

│   │   └── products_handler.php

│   ├── includes/

│   │   ├── footer.php

│   │   └── header.php

│   └── sql/

│       └── schema.sql

└── public_html/             # Корень сайта (фронтенд)

    ├── .htaccess
    
    ├── index.php
    
    ├── catalog.php
    
    ├── product.php
    
    ├── cart.php
    
    ├── about.php
    
    ├── login.php
    
    ├── profile.php
    
    ├── admin.html
    
    ├── api/                 # Прослойки к серверной части
    
    │   ├── products.php
    
    │   ├── order.php
    
    │   └── admin_orders.php
    
    ├── css/
    
    │   ├── style.css
    
    │   └── hive.css
    
    ├── js/
    
    │   ├── main.js
    
    │   ├── hive.js
    
    │   └── auth.js
    
    └── img/                 # (Сюда положишь свои картинки и видео)

## Локальный запуск

1. Скопируй `app/.env.example` в `app/.env` и заполни данные.
2. Создай БД в MySQL и импортируй `app/sql/schema.sql`.
3. Настрой веб-сервер так, чтобы `public_html/` был корнем сайта.
4. Открой сайт в браузере.

## Развёртывание на Beget

См. инструкцию в чате / заметках.

## Автор

Утин М.А., группа 454.
