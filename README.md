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
vendor/bin/codecept run unit
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

Swagger UI доступен по адресу: [http://localhost:8080/docs](http://localhost:8080/docs)

---

## 🛠 Требования

- PHP 8.1+
- Composer
- MySQL
- Vagrant (опционально)

---

## 🧾 Лицензия

MIT License.