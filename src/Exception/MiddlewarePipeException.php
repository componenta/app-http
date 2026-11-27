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
        public readonly string $definitionType,
        string $file,
        int $line,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                'Failed to pipe middleware %s in %s on line %d',
                $definitionType,
                $file,
                $line,
            ),
            0,
            $previous,
        );

        $this->file = $file;
        $this->line = $line;
    }
}
