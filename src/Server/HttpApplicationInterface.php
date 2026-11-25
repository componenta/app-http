<?php

declare(strict_types=1);

namespace Componenta\App\Server;

use Componenta\App\AppInterface;

/** Runnable HTTP application, independent of the configuration-only boot target. */
interface HttpApplicationInterface extends AppInterface
{
    public function pipe(mixed $middleware, int $priority = 0): void;
}
