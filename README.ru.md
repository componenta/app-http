# Componenta App HTTP

Интеграция HTTP-рантайма для Componenta-приложений. Пакет связывает `componenta/app` с PSR-15 конвейером промежуточных обработчиков, созданием серверного запроса и отправкой PSR-7 ответа.

Используйте этот пакет, когда приложение должно обрабатывать HTTP-запросы. Маршрутизация, разбор тела запроса, помощники ответов и конкретные реализации PSR-7 находятся в отдельных пакетах.

## Установка

```bash
composer require componenta/app-http
```

Пакет публикует `Componenta\App\Server\ConfigProvider` через метаданные Composer.

## Зависимости

Пакет требует PHP `^8.4`, `componenta/app`, `componenta/config`, `componenta/http-emitter`, `componenta/http-psr`, `componenta/middleware-factory`, `componenta/path-resolver`, `componenta/pipeline`, `nyholm/psr7-server`, PSR-11 и контракты PSR-15 промежуточных обработчиков.

## Что регистрирует пакет

`ConfigProvider` регистрирует:

| Сервис или ключ конфигурации | Назначение |
|---|---|
| `HttpAppAdapter` | Создает HTTP-приложение для HTTP-области. |
| `HttpBootTargetAdapter` | Адаптирует HTTP-приложение к точке загрузки. |
| `HttpBootloader` | Загружает настроенные промежуточные обработчики в HTTP-конвейер. |
| `App` | Реализация HTTP-приложения. |

## Поведение

HTTP-приложение создает серверный запрос через `componenta/http-psr`, пропускает его через настроенный конвейер промежуточных обработчиков и отправляет полученный PSR-7 ответ через `componenta/http-emitter`.

Во время HTTP-загрузки `HttpBootloader` подключает `config/pipeline.php` через настроенный резолвер путей. Этот файл должен вызывать `$app->pipe(...)` на HTTP-точке загрузки.

```php
<?php

declare(strict_types=1);

use Componenta\App\Boot\Target\HttpBootTargetInterface;

/** @var HttpBootTargetInterface $app */
$app->pipe(App\Http\Middleware\ExampleMiddleware::class);
```

Этот пакет сам не добавляет маршруты. Зарегистрируйте `componenta/router-app`, если промежуточные обработчики маршрутизации должны добавляться при загрузке.

## Связанные пакеты

- [`componenta/http-psr`](https://github.com/componenta/http-psr/blob/main/README.ru.md) создает экземпляры `ServerRequestInterface`.
- [`componenta/http-emitter`](https://github.com/componenta/http-emitter/blob/main/README.ru.md) отправляет PSR-7 ответы.
- [`componenta/router-app`](https://github.com/componenta/router-app/blob/main/README.ru.md) добавляет промежуточные обработчики роутера в HTTP-загрузку.
