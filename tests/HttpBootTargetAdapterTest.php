<?php

declare(strict_types=1);

use Componenta\App\AppInterface;
use Componenta\App\Boot\HttpBootTargetAdapter;
use Componenta\App\Boot\Target\HttpBootTargetInterface;
use Componenta\App\Scope;
use Componenta\App\Server\HttpApplicationInterface;

it('always hides a runnable HTTP application behind a separate configuration capability', function (): void {
    $app = new class implements HttpApplicationInterface, HttpBootTargetInterface {
        public array $piped = [];
        public bool $ran = false;
        public function run(): ?int { $this->ran = true; return null; }
        public function pipe(mixed $middleware, int $priority = 0): void { $this->piped[] = [$middleware, $priority]; }
    };

    $target = (new HttpBootTargetAdapter())->create($app, Scope::HTTP);

    expect($target)->toBeInstanceOf(HttpBootTargetInterface::class)
        ->not->toBe($app)
        ->not->toBeInstanceOf(AppInterface::class)
        ->and(is_callable([$target, 'run']))->toBeFalse();

    $middleware = new stdClass();
    $target->pipe($middleware, 15);
    expect($app->piped)->toBe([[$middleware, 15]])->and($app->ran)->toBeFalse();
});

it('rejects applications that cannot serve as an HTTP boot target', function (): void {
    $app = new class implements AppInterface {
        public function run(): ?int { return null; }
    };

    expect(fn (): object => (new HttpBootTargetAdapter())->create($app, Scope::HTTP))
        ->toThrow(LogicException::class, HttpApplicationInterface::class);
});
