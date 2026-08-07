<?php

declare(strict_types=1);

use Componenta\App\AppInterface;
use Componenta\App\Boot\HttpBootTargetAdapter;
use Componenta\App\Boot\Target\HttpBootTarget;
use Componenta\App\Boot\Target\HttpBootTargetInterface;
use Componenta\App\Scope;
use Componenta\App\Server\App as HttpApp;

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
it('wraps the standard HTTP application in an HTTP boot target', function (): void {
    $app = (new ReflectionClass(HttpApp::class))->newInstanceWithoutConstructor();

    expect((new HttpBootTargetAdapter())->create($app, Scope::HTTP))
        ->toBeInstanceOf(HttpBootTarget::class);
});

it('rejects applications that cannot serve as an HTTP boot target', function (): void {
    $app = new class implements AppInterface {
        public function run(): ?int
        {
            return null;
        }
    };

    expect(fn (): object => (new HttpBootTargetAdapter())->create($app, Scope::HTTP))
        ->toThrow(LogicException::class, HttpApp::class);
});