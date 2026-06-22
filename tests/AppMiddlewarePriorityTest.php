<?php

declare(strict_types=1);

use Componenta\App\Server\App;
use Componenta\Http\EmitterInterface;
use Componenta\Http\Middleware\MiddlewareFactory;
use Componenta\Http\Middleware\PipelineFactoryInterface;
use Componenta\Http\Middleware\PipelineInterface;
use Componenta\Http\Middleware\Resolver\MiddlewareResolverInterface;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class AppHttpPriorityMiddleware implements MiddlewareInterface
{
    public function __construct(
        public string $name,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        return $handler->handle($request);
    }
}

final class AppHttpPriorityPipelineFactory implements PipelineFactoryInterface
{
    /** @var list<string> */
    public array $received = [];

    public function createMiddlewarePipeline(
        iterable $middlewares = [],
        ?RequestHandlerInterface $fallbackHandler = null,
    ): PipelineInterface {
        foreach ($middlewares as $middleware) {
            if ($middleware instanceof AppHttpPriorityMiddleware) {
                $this->received[] = $middleware->name;
            }
        }

        return new AppHttpPriorityPipeline();
    }
}

final readonly class AppHttpPriorityPipeline implements PipelineInterface
{
    public function pipe(iterable|MiddlewareInterface $middlewares): PipelineInterface
    {
        return $this;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        return $handler->handle($request);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response();
    }
}

final readonly class AppHttpPriorityMiddlewareResolver implements MiddlewareResolverInterface
{
    public function resolve(mixed $middleware): ?MiddlewareInterface
    {
        return null;
    }
}

final readonly class AppHttpPriorityRequestCreator implements ServerRequestCreatorInterface
{
    public function fromGlobals(): ServerRequestInterface
    {
        return new ServerRequest('GET', '/');
    }

    public function fromArrays(
        array $server,
        array $headers = [],
        array $cookie = [],
        array $get = [],
        ?array $post = null,
        array $files = [],
        $body = null,
    ): ServerRequestInterface {
        return new ServerRequest('GET', '/');
    }

    public static function getHeadersFromServer(array $server): array
    {
        return [];
    }
}

final class AppHttpPriorityEmitter implements EmitterInterface
{
    public ?ResponseInterface $response = null;

    public function emit(ResponseInterface $response): void
    {
        $this->response = $response;
    }
}

it('orders HTTP middleware by priority and preserves registration order for equal priority', function (): void {
    $pipelineFactory = new AppHttpPriorityPipelineFactory();
    $app = new App(
        new AppHttpPriorityRequestCreator(),
        new AppHttpPriorityEmitter(),
        $pipelineFactory,
        new MiddlewareFactory(new AppHttpPriorityMiddlewareResolver()),
    );

    $app->pipe(new AppHttpPriorityMiddleware('default-a'));
    $app->pipe(new AppHttpPriorityMiddleware('low'), priority: -100);
    $app->pipe(new AppHttpPriorityMiddleware('high'), priority: 100);
    $app->pipe(new AppHttpPriorityMiddleware('default-b'));

    $app->run();

    expect($pipelineFactory->received)->toBe([
        'high',
        'default-a',
        'default-b',
        'low',
    ]);
});
