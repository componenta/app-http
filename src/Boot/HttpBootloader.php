<?php

declare(strict_types=1);

namespace Componenta\App\Boot;

use Componenta\App\Boot\Target\HttpBootTargetInterface;
use Componenta\Stdlib\PathResolverInterface;
use Componenta\App\Scope;
use Componenta\Scope\Scopes;

final class HttpBootloader implements BootloaderInterface
{
    use ScopedBootloaderSupport;

    private const string PIPELINE_FILE = 'config/pipeline.php';

    public function __construct(
        private readonly PathResolverInterface $paths,
    ) {}

    public Scopes $scopes {
        get => Scopes::of(Scope::HTTP);
    }

    public function boot(BootContext $context): void
    {
        $app = $context->target(HttpBootTargetInterface::class);

        require $this->paths->resolve(self::PIPELINE_FILE);
    }

}
