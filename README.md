# Ledger App — Бухгалтерская книга на Laravel + MoonShine

Система учёта финансовых транзакций с двойной записью (дебет/кредит).  
Административный интерфейс реализован на **MoonShine**, REST API — на **Laravel Sanctum**.

---

## Функциональные возможности

- CRUD для счетов (актив, пассив, капитал, доходы, расходы)
- CRUD для транзакций с отдельными проводками
- Валидация равенства дебета и кредита при проведении транзакции
- Запрет редактирования/удаления проведённых транзакций
- Автоматический расчёт остатка по счёту
- Фильтрация транзакций по дате
- Экспорт транзакций в CSV
- Отчёт «Оборотно-сальдовая ведомость» за выбранный период
- REST API с аутентификацией через Sanctum
- Unit-тесты PHPUnit

---

## Установка и запуск

### 1. Клонируйте репозиторий

```bash
git clone https://github.com/ваш-username/ledger-app.git
cd ledger-app
```

### 2. Настройте окружение

#### Скопируйте файл .env.example в .env
```bash 
cp .env.example .env
```

#### Отредактируйте .env, укажите настройки базы данных (для Sail они уже предустановлены)
```bash
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=ledger
DB_USERNAME=sail
DB_PASSWORD=password
```

### 3. Запустите контейнеры через Laravel Sail

```bash
composer install
./vendor/bin/sail up -d
```

### 4. Выполните миграции и сидеры

```bash
./vendor/bin/sail artisan migrate --seed
```

### 5. Создайте пользователя MoonShine

```bash
./vendor/bin/sail artisan moonshine:user
```

#### Следуйте инструкциям (укажите email и пароль для входа в админку).

### 6. Откройте сайт

#### Главная страница: http://localhost (приветственная страница Laravel)
#### Админ-панель: http://localhost/admin (то, что было реализовано)

---

## Информация о проекте

### Структура данных

- Accounts — счета (name, code, type, is_active)
- Transactions — транзакции (date, description, posted)
- JournalEntries — проводки (transaction_id, account_id, amount, type)

### Тестирование

#### Запустите Unit-тесты:
```bash
./vendor/bin/sail artisan test --testsuite=Unit
```

### Экспорт транзакций

На странице списка транзакций в админ-панели есть кнопка «Экспорт в CSV».
Файл скачивается в кодировке Windows-1251 с правильным разделителем для Excel.

### Отчёт «Оборотно-сальдовая ведомость»

В меню админ-панели есть пункт «Оборотно-сальдовая ведомость».
Выберите период и нажмите «Сформировать» — вы увидите таблицу с остатками на начало, оборотами и остатками на конец по каждому счёту.

### REST API

API доступно по адресу /api.
Для аутентификации используется Laravel Sanctum (токен передаётся в заголовке Authorization: Bearer {token}).

### Технологии

- PHP 8.2+
- Laravel 12+
- PostgreSQL 16
- MoonShine 4.x
- Laravel Sail (Docker)
- Laravel Sanctum
- PHPUnit

### Дополнительные команды

Остановка контейнеров:

```bash
./vendor/bin/sail down
```

Перезапуск:

```bash
./vendor/bin/sail restart
```

Вход в контейнер:

```bash
./vendor/bin/sail shell
```
### Лицензия

Проект разработан в рамках практики. Все права защищены.

### Автор

ch4inz92
