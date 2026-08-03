<?php

declare(strict_types=1);

use Componenta\App\AppInterface;
use Componenta\App\Boot\HttpBootTargetAdapter;
use Componenta\App\Boot\Target\HttpBootTargetInterface;
use Componenta\App\Scope;

it('uses an HTTP-capable application as its own boot target', function (): void {
    $app = new class implements AppInterface, HttpBootTargetInterface {
        public function run(): ?int
        {
            return null;
        }

        public function pipe(mixed $middleware, int $priority = 0): void {}
    };

    $target = (new HttpBootTargetAdapter())->create($app, Scope::HTTP);

    expect($target)->toBe($app);
});
