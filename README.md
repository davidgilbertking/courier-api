# 📦 Courier API — Тестовое задание на позицию PHP Backend Developer

REST API для управления курьерами, транспортом и заявками. Реализовано с использованием Yii2, OpenAPI (Swagger), Codeception и MariaDB.

---

## 🚀 Быстрый старт

### 🔧 Требования

- PHP >= 7.4
- Composer
- MariaDB / MySQL
- Yii2 (установлен автоматически через Composer)

---

## 📁 Установка

```bash
git clone https://github.com/your-username/courier-api.git
cd courier-api
composer install
```

---

## 🗄️ Настройка базы данных

Создай базу данных `courier_api` и пользователя `devuser`:

```sql
CREATE DATABASE courier_api CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'devuser'@'localhost' IDENTIFIED BY 'secret123';
GRANT ALL PRIVILEGES ON courier_api.* TO 'devuser'@'localhost';
FLUSH PRIVILEGES;
```

Применить миграции:

```bash
php yii migrate
```

---

## 🧪 Запуск тестов

```bash
vendor/bin/codecept run unit
```

🧼 Все тесты используют транзакции и не оставляют следов в базе данных.

---

## 📚 Документация API

Файл `openapi.yaml` описывает весь API. Чтобы отобразить его в браузере:

1. Убедись, что в `web/docs` лежит Swagger UI (статические файлы).
2. Запусти встроенный сервер:

```bash
php -S localhost:8080 -t web
```

3. Перейди в браузере на:  
👉 http://localhost:8080/docs

---

## 🔐 Аутентификация

Все методы (кроме GET-запросов) требуют `X-Api-Key` в заголовке.

📌 Токен создается автоматически при создании курьера (`api_token`).

Пример:

```http
X-Api-Key: z9J19TfW0zv48yD3p2NBhXk7cYruqyD7qPGoSlV2HLRQ3eLo9LqRxmDwVjGm1aQz
```

---

## 🔁 Примеры CURL-запросов

📍 Создать курьера:

```bash
curl -X POST http://localhost:8080/couriers \
  -H "Content-Type: application/json" \
  -d '{
    "role": "main",
    "email": "example@example.com",
    "first_name": "John",
    "last_name": "Doe"
}'
```

📍 Получить список транспорта:

```bash
curl -X GET http://localhost:8080/vehicles?courier_id=1 \
  -H "X-Api-Key: {your_token}"
```

---

## 🧩 Структура проекта

```
├── config/               # Конфигурация Yii
├── controllers/          # REST-контроллеры
├── migrations/           # Миграции базы данных
├── models/               # ActiveRecord модели
├── tests/                # Юнит-тесты Codeception
├── web/                  # Веб-директория
│   ├── docs/             # Swagger UI
│   └── openapi.yaml      # Спецификация OpenAPI 3
```

---

## ✅ Реализовано

- [x] Курьеры
- [x] Транспорт
- [x] Заявки
- [x] Soft delete
- [x] API-токен
- [x] Swagger документация
- [x] Фильтрация и валидация
- [x] Тесты моделей
