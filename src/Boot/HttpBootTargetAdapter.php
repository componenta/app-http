<?php

declare(strict_types=1);

namespace Componenta\App\Boot;

use Componenta\App\AppInterface;
use Componenta\App\Boot\Target\HttpBootTarget;
use Componenta\App\Boot\Target\HttpBootTargetInterface;
use Componenta\App\Scope;
use Componenta\Scope\ScopeInterface;
use LogicException;

final readonly class HttpBootTargetAdapter implements BootTargetAdapterInterface
{
    public function supports(ScopeInterface $scope): bool
    {
        return $scope->matches(Scope::HTTP);
    }

    public function create(AppInterface $app, ScopeInterface $scope): HttpBootTargetInterface
    {
        if ($app instanceof HttpBootTargetInterface) {
            return $app;
        }

        if (!$app instanceof App) {
            throw new LogicException(sprintf(
                'Scope "%s" expects app %s, %s given.',
                $scope->value,
                HttpApp::class,
                $app::class,
            ));
        }

        return new HttpBootTarget($app);
    }
}
