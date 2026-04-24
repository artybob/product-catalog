# Product Catalog - Тестовое задание Junior Full-Stack Developer

Веб-приложение каталог товаров с административной панелью на Laravel + Vue.js (InertiaJS).

## 🚀 Технологии

- **Backend:** Laravel 11, PHP 8.4, PostgreSQL
- **Frontend:** Vue 3 (Composition API), InertiaJS, Tailwind CSS, Pinia
- **Authentication:** Laravel Sanctum (API) + Session-based (Web)
- **Tools:** Docker, Composer, Nginx, Vite

## 📋 Функционал

### Публичная часть
- Просмотр списка товаров с пагинацией (15 товаров на страницу)
- Фильтрация товаров по категориям
- Детальная карточка товара

### Административная часть
- Аутентификация администратора
- Управление товарами (CRUD)
- Создание/редактирование с валидацией
- Удаление с подтверждением
- Поиск по названию товара
- Фильтрация по категориям

## 🐳 Установка через Docker

### Предварительные требования
- Docker
- Docker Compose

### Шаги установки

```bash
# 1. Клонировать репозиторий
git clone <repository-url>
cd product-catalog

# 2. Скопировать .env файл
cp .env.example .env

# 3. Запустить контейнеры
docker-compose up -d --build

# 4. Установить зависимости PHP
docker-compose exec app composer install

# 5. Сгенерировать ключ приложения
docker-compose exec app php artisan key:generate

# 6. Запустить миграции и сидеры
docker-compose exec app php artisan migrate:fresh --seed

# 7. Установить зависимости Node и собрать фронтенд
docker-compose exec app npm install
docker-compose exec app npm run build

# 8. Настроить права доступа (если нужно)
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

### Тестовые учетные данные

Email: admin@example.com
Password: password123

## 🛠️ Команды для разработки

```bash
# Запустить миграции
docker-compose exec app php artisan migrate

# Запустить сидеры
docker-compose exec app php artisan db:seed

# Очистить кэш
docker-compose exec app php artisan optimize:clear

# Запустить Vite в режиме разработки
docker-compose exec app npm run dev

# Собрать фронтенд для production
docker-compose exec app npm run build

# Зайти в tinker
docker-compose exec app php artisan tinker
```

