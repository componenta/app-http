<?php

declare(strict_types=1);

namespace Componenta\App\Boot;

use Componenta\App\AppInterface;
use Componenta\App\Boot\Target\HttpBootTarget;
use Componenta\App\Scope;
use Componenta\App\Server\HttpApplicationInterface;
use Componenta\Scope\ScopeInterface;
use LogicException;

final readonly class HttpBootTargetAdapter implements BootTargetAdapterInterface
{
    public function supports(ScopeInterface $scope): bool
    {
        return $scope->matches(Scope::HTTP);
    }

    public function create(AppInterface $app, ScopeInterface $scope): object
    {
        if (!$app instanceof HttpApplicationInterface) {
            throw new LogicException(sprintf(
                'Scope "%s" expects app implementing %s, %s given.',
                $scope->value,
                HttpApplicationInterface::class,
                $app::class,
            ));
        }

        return new HttpBootTarget($app);
    }
}
