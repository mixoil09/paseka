# Интернет-магазин «Пасека»

Курсовая работа по МДК 04.03 «Разработка клиентской части информационных ресурсов».

## Стек технологий
- **Frontend:** HTML5, CSS3, Vanilla JS (fetch, localStorage), React (для админ-панели).
- **Backend:** PHP 8.2, MySQL (PDO).
- **Хостинг:** Beget.

## Установка
1. Скопируйте `.env.example` в `app/.env` и заполните данные БД.
2. Импортируйте `app/sql/schema.sql` в вашу базу данных MySQL.
3. Убедитесь, что корень веб-сервера указывает на папку `public_html`.

## Тестовый доступ
- **Админ:** `admin@paseka.ru` / `admin123`

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


## Автор

Утин М.А., группа 454.
