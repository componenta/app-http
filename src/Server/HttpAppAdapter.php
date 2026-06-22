<?php

declare(strict_types=1);

namespace Componenta\App\Server;

use Componenta\App\AppAdapterInterface;
use Componenta\App\AppInterface;
use Componenta\App\Scope;
use Componenta\Config\ContainerValue;
use Componenta\Scope\ScopeInterface;

final readonly class HttpAppAdapter implements AppAdapterInterface
{
    public function supports(ScopeInterface $scope): bool
    {
        return $scope->matches(Scope::HTTP);
    }

    public function createApp(ScopeInterface $scope, ContainerValue $container): AppInterface
    {
        return App::createFromContainer($container);
    }
}
