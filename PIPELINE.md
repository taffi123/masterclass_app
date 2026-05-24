# CI/CD pipeline для Laravel

## Назначение

Пайплайн проверяет Laravel-приложение при каждом `push` и `pull_request` в долгоживущие ветки:

- `develop` / `dev`
- `uat` / `qa`
- `main` / `master`

Конфигурация находится в `.github/workflows/ci.yml`.

## Среды

В проект добавлены отдельные конфигурационные файлы окружений:

- `.env.dev` - окружение разработки.
- `.env.uat` - окружение пользовательского тестирования.
- `.env.prod` - продуктивное окружение.
- `.env.ci` - окружение для запуска пайплайна. Используется SQLite in-memory и отключён debug-режим.

Основной файл `.env` не хранится в репозитории. Он добавлен в `.gitignore`.

## Этапы пайплайна

### 1. Tests with coverage gate

Этап выполняет подготовку Laravel-приложения, миграции и тесты:

```bash
cp .env.ci .env
php artisan key:generate --force
php artisan migrate --force
php artisan test --coverage --min=50
```

Если хотя бы один тест падает или покрытие ниже 50%, pipeline завершается с ошибкой.

### 2. PHPStan / Larastan

Статический анализ выполняется через Larastan:

```bash
./vendor/bin/phpstan analyse --error-format=github
```

Конфигурация анализа находится в `phpstan.neon`. При любой ошибке статического анализа pipeline завершается с ошибкой.

### 3. Laravel Pint lint check

Линтер запускается в режиме проверки, без автоматического исправления кода:

```bash
./vendor/bin/pint --test
```

Если Laravel Pint находит нарушение форматирования, pipeline завершается с ошибкой.

### 4. Deploy simulation

Симуляция деплоя запускается только после успешного прохождения тестов, статического анализа и линтера.

Соответствие веток и окружений:

| Ветка | Файл окружения | Сообщение |
| --- | --- | --- |
| `develop` / `dev` | `.env.dev` | `Deploying to DEV with .env.dev` |
| `uat` / `qa` | `.env.uat` | `Deploying to UAT with .env.uat` |
| `main` / `master` | `.env.prod` | `Deploying to PROD with .env.prod` |

Для продуктивной среды используется GitHub Environment `production`. В настройках репозитория нужно добавить обязательного reviewer для environment `production`, чтобы получить ручной approval перед выполнением production deploy simulation.

## Уведомление maintainers

В конце pipeline есть job `notify`, который выводит результат pipeline, ветку и SHA коммита. При необходимости этот шаг можно заменить отправкой уведомления в Telegram, Slack или email.

## Как проверить лабораторную

1. Создать GitHub-репозиторий.
2. Загрузить проект.
3. Создать ветки `develop`, `uat`, `main`.
4. Настроить GitHub Environment `production` и добавить reviewer.
5. Сделать push в `develop` и проверить успешный pipeline.
6. Для скриншота ошибки тестов временно изменить любой ожидаемый результат в тесте.
7. Для скриншота ошибки linter временно нарушить форматирование PHP-файла и запустить pipeline.
