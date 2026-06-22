<?php

declare(strict_types=1);

namespace Componenta\App\Exception;

use Componenta\Http\Middleware\Exception\MiddlewareResolutionExceptionInterface;

/**
 * Exception thrown when middleware piping fails.
 *
 * Captures the location where pipe() was called to aid debugging.
 */
final class MiddlewarePipeException extends \RuntimeException implements MiddlewareResolutionExceptionInterface
{
    public function __construct(
        public readonly mixed $middleware,
        string $file,
        int $line,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                'Failed to pipe middleware %s in %s on line %d',
                self::formatMiddleware($middleware),
                $file,
                $line,
            ),
            0,
            $previous,
        );

        $this->file = $file;
        $this->line = $line;
    }

    private static function formatMiddleware(mixed $middleware): string
    {
        return match (true) {
            is_string($middleware) => "\"$middleware\"",
            is_object($middleware) => $middleware::class,
            is_array($middleware) => 'array',
            default => get_debug_type($middleware),
        };
    }
}