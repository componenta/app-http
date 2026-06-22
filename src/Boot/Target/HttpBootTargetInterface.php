<?php

declare(strict_types=1);

namespace Componenta\App\Boot\Target;

interface HttpBootTargetInterface
{
    public function pipe(mixed $middleware, int $priority = 0): void;
}
