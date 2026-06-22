# Componenta App HTTP

HTTP runtime integration for Componenta applications. The package connects `componenta/app` with a PSR-15 middleware pipeline, server request creation, and PSR-7 response emitting.

Use this package when the application must handle HTTP requests. Routing, body parsing, response helpers, and concrete PSR-7 implementations live in separate packages.

## Installation

```bash
composer require componenta/app-http
```

The package exposes `Componenta\App\Server\ConfigProvider` through Composer metadata.

## Dependencies

The package requires PHP `^8.4`, `componenta/app`, `componenta/config`, `componenta/http-emitter`, `componenta/http-psr`, `componenta/middleware-factory`, `componenta/path-resolver`, `componenta/pipeline`, `nyholm/psr7-server`, PSR-11, and PSR-15 middleware contracts.

## Registered Services

`ConfigProvider` registers:

| Service or config key | Purpose |
|---|---|
| `HttpAppAdapter` | Creates an HTTP application for the HTTP scope. |
| `HttpBootTargetAdapter` | Adapts the HTTP application to a boot target. |
| `HttpBootloader` | Loads configured middleware into the HTTP pipeline. |
| `App` | The HTTP application implementation. |

## Runtime Behavior

The HTTP app creates a server request through `componenta/http-psr`, runs it through the configured middleware pipeline, and emits the resulting PSR-7 response through `componenta/http-emitter`.

During HTTP boot, `HttpBootloader` requires `config/pipeline.php` through the configured path resolver. That file is responsible for calling `$app->pipe(...)` on the HTTP boot target.

```php
<?php

declare(strict_types=1);

use Componenta\App\Boot\Target\HttpBootTargetInterface;

/** @var HttpBootTargetInterface $app */
$app->pipe(App\Http\Middleware\ExampleMiddleware::class);
```

This package does not add routes by itself. Register `componenta/router-app` when routing middleware should be piped during boot.

## Related Packages

- [`componenta/http-psr`](https://github.com/componenta/http-psr/blob/main/README.md) creates `ServerRequestInterface` instances.
- [`componenta/http-emitter`](https://github.com/componenta/http-emitter/blob/main/README.md) emits PSR-7 responses.
- [`componenta/router-app`](https://github.com/componenta/router-app/blob/main/README.md) adds router middleware to the HTTP boot process.
