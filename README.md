# Courier API

REST API для управления курьерами, транспортом и заявками курьеров. Реализовано на Yii2.

## 📦 Возможности

- CRUD для курьеров, транспорта и заявок
- Авторизация по API ключу
- Swagger UI (`/docs`)
- Поддержка CORS
- Мягкое удаление заявок
- Unit-тесты для моделей

## 🚀 Установка (локально, без Vagrant)

```bash
git clone https://github.com/davidgilbertking/courier-api.git
cd courier-api
composer install
cp .env.example .env # если требуется
php yii migrate
php -S localhost:8080 -t web
```

Откройте [http://localhost:8080/docs](http://localhost:8080/docs) для Swagger UI.

---

## 🧪 Тесты

```bash
vendor/bin/codecept run
```

---

## 🐘 Использование Vagrant (альтернатива ручной установке)

Если вы предпочитаете разворачивать окружение с помощью **Vagrant**, выполните следующие шаги:

1. Убедитесь, что установлены необходимые плагины:

```bash
vagrant plugin install vagrant-hostmanager vagrant-vbguest
```

2. Скопируйте пример конфигурации:

```bash
cp vagrant/config/vagrant-local.example.yml vagrant/config/vagrant-local.yml
```

3. Замените `github_token` на ваш персональный токен GitHub:  
   Получить его можно здесь: https://github.com/settings/tokens

4. Запустите виртуальную машину:

```bash
vagrant up
```

После запуска приложение будет доступно по адресу: http://yii2basic.test (или IP из `vagrant-local.yml`)

> ⚠️ Vagrant **не обязателен** — вы можете установить проект вручную по инструкции выше.

---

## 📄 Документация

Документация OpenAPI доступна в `/openapi.yaml` и в Swagger UI по адресу `/docs`.

---

## ⚙️ Swagger UI

Документация доступна по адресу:

```
http://localhost:8080/docs
```

Для тестирования защищённых эндпойнтов (POST, PUT, DELETE) через Swagger UI необходимо:

1. **Создать курьера с ролью `main`** (в Swagger'e или через команду `curl`):

   ```bash
   curl -X 'POST' \
     'http://localhost:8080/couriers' \
     -H 'accept: application/json' \
     -H 'Content-Type: application/json' \
     -d '{
       "role": "main",
       "email": "admin@example.com",
       "first_name": "Admin",
       "last_name": "User"
     }'
   ```

2. **Получить токен авторизации (api_token)**:

   ```bash
   curl -X 'GET' \
     'http://localhost:8080/couriers?role=main' \
     -H 'accept: application/json'
   ```

   Найдите в ответе поле `api_token` у созданного курьера.

3. **Открыть Swagger UI** → нажать **Authorize** (в правом верхнем углу), вставить токен в поле:

   ```
   X-Api-Key: <ваш API токен>
   ```

4. Нажмите **Authorize**, чтобы использовать Swagger как полноценный клиент для тестирования API.


## 🛠 Требования

- PHP 8.1+
- Composer
- MySQL
- Vagrant (опционально)

---

## 🧾 Лицензия

MIT License.