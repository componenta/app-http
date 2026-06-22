<?php

declare(strict_types=1);

namespace Componenta\App\Server;

use Componenta\App\AppInterface;
use Componenta\App\Exception\MiddlewarePipeException;
use Componenta\Http\EmitterInterface;
use Componenta\Http\Middleware\Exception\MiddlewareResolutionExceptionInterface;
use Componenta\Http\Middleware\MiddlewareFactory;
use Componenta\Http\Middleware\PipelineFactoryInterface;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\MiddlewareInterface;

final class App implements AppInterface
{
    /**
     * @var list<array{middleware: MiddlewareInterface, priority: int, order: int}>
     */
    private array $middlewares = [];

    private int $middlewareOrder = 0;

    public function __construct(
        private readonly ServerRequestCreatorInterface $requestCreator,
        private readonly EmitterInterface              $emitter,
        private readonly PipelineFactoryInterface      $pipelineFactory,
        private readonly MiddlewareFactory             $middlewareFactory,
    ) {}

    public function run(): ?int
    {
        $request = $this->requestCreator->fromGlobals();
        $pipeline = $this->pipelineFactory->createMiddlewarePipeline($this->sortedMiddlewares());

        $response = $pipeline->handle($request);

        $this->emitter->emit($response);

        return null;
    }

    /**
     * @throws MiddlewareResolutionExceptionInterface
     */
    public function pipe(mixed $middleware, int $priority = 0): void
    {
        try {
            $this->middlewares[] = [
                'middleware' => $this->middlewareFactory->createMiddleware($middleware),
                'priority' => $priority,
                'order' => $this->middlewareOrder++,
            ];
        } catch (MiddlewareResolutionExceptionInterface $e) {
            $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];

            throw new MiddlewarePipeException(
                $e->middleware,
                $caller['file'] ?? 'unknown',
                $caller['line'] ?? 0,
                $e,
            );
        }
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public static function createFromContainer(ContainerInterface $container): App
    {
        return new self(
            $container->get(ServerRequestCreatorInterface::class),
            $container->get(EmitterInterface::class),
            $container->get(PipelineFactoryInterface::class),
            $container->get(MiddlewareFactory::class),
        );
    }

    /**
     * @return list<MiddlewareInterface>
     */
    private function sortedMiddlewares(): array
    {
        $entries = $this->middlewares;

        usort(
            $entries,
            static fn (array $left, array $right): int => $right['priority'] <=> $left['priority']
                ?: $left['order'] <=> $right['order'],
        );

        return array_column($entries, 'middleware');
    }
}
