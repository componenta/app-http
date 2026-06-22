<?php

declare(strict_types=1);

namespace Componenta\App\Boot;

use Componenta\App\AppInterface;
use Componenta\App\Boot\Target\HttpBootTarget;
use Componenta\App\Scope;
use Componenta\Scope\ScopeInterface;
use Componenta\App\Server\App as HttpApp;
use LogicException;

final readonly class HttpBootTargetAdapter implements BootTargetAdapterInterface
{
    public function supports(ScopeInterface $scope): bool
    {
        return $scope->matches(Scope::HTTP);
    }

    public function create(AppInterface $app, ScopeInterface $scope): object
    {
        if (!$app instanceof HttpApp) {
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
