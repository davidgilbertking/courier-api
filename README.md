# Courier API

REST API для управления курьерами, транспортом и заявками курьеров.  
Реализовано с использованием **Yii2 Framework**, **MySQL**, **Swagger UI** и **Codeception**.

---

## ⚙️ Установка и запуск через Vagrant

1. Клонируйте репозиторий:

```bash
git clone https://github.com/davidgilbertking/courier-api.git
cd courier-api
```

2. Поднимите виртуальную машину:

```bash
vagrant up
```

3. Подключитесь к машине:

```bash
vagrant ssh
cd /vagrant/courier-api
```

4. Установите зависимости и выполните миграции:

```bash
composer install
php yii migrate
```

5. Запустите встроенный сервер:

```bash
php -S localhost:8080 -t web
```

6. Откройте Swagger UI по адресу:

```
http://localhost:8081/docs
```

> Swagger UI отображает документацию из файла `web/openapi.yaml`.

---

## 🔑 Авторизация

API использует API-ключ, который передаётся в заголовке:

```
X-Api-Key: <токен>
```

Токен создается автоматически при сохранении нового курьера.  
Курьеры с ролью `main` имеют доступ к методам POST, PUT, DELETE.

---

## ✅ Тестирование

Для запуска unit-тестов используется **Codeception**.

Тесты находятся в папке:

```
tests/unit/models
```

Запуск тестов:

```bash
vendor/bin/codecept run unit
```

Тестируются модели:
- `Courier`
- `Vehicle`
- `CourierRequest`

Каждый тест изолирован с помощью транзакций и охватывает:
- валидации
- уникальность полей
- связи между моделями
- генерацию API-токенов

---

## 📝 Документация OpenAPI

Файл `web/openapi.yaml` содержит спецификацию API.  
Swagger UI использует этот файл для генерации документации.

---

## 📁 Структура проекта

- `models/` — модели ActiveRecord
- `controllers/` — REST-контроллеры
- `migrations/` — миграции для MySQL
- `web/` — публичная директория
- `web/docs/` — Swagger UI
- `tests/` — unit-тесты Codeception

---

## 👤 Автор

[David Gilbert King](https://github.com/davidgilbertking)