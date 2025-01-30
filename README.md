Это старая дев версия микросерсервиса СКАДА системы, взаимодействие происходит через апи.

# SCADA Service

## Установка

1. `composer install`
2. Создать .env из .env.example
3. Запуск `docker compose up -d --build`

## Docker

По дефолту в `.env` заданы порты:

Порты внешние:
- CONTAINER_APP_PORT=8080
- CONTAINER_GRPC_PORT=8081
- CONTAINER_RPC_PORT=6001
- CONTAINER_VITE_PORT=5173

Порты внутренние:
- APP_PORT=8080
- GRPC_PORT=8081
- RPC_PORT=6001
- VITE_PORT=5173

Порты базы:
- FORWARD_DB_PORT=5435
- FORWARD_REDIS_PORT=6375

## Meilisearch

Update Meilisearch index settings command:`php artisan scout:sync-index-settings`

## Swagger

https://github.com/DarkaOnLine/L5-Swagger

Генерация `php artisan l5-swagger:generate`

Документация `https://github.com/DarkaOnLine/L5-Swagger/wiki/Installation-&-Configuration`

## Seeding

Для наполнения БД тестовыми данными запускаем `php artisan db:seed`

## DTO

Отказались от laravel-data, обновить доку

<!--https://spatie.be/docs/laravel-data/v4 

Cache structures: `php artisan data:cache-structures`
-->




