<?php

declare(strict_types=1);

namespace Componenta\App\Server;

use Componenta\App\Boot\HttpBootloader;
use Componenta\App\Boot\HttpBootTargetAdapter;
use Componenta\App\ConfigKey as AppConfigKey;
use Componenta\App\Scope;
use Componenta\Config\ConfigProvider as BaseConfigProvider;
use Psr\Container\ContainerInterface;

final class ConfigProvider extends BaseConfigProvider
{
    protected function getFactories(): array
    {
        return [
            App::class => static fn (ContainerInterface $container): App => App::createFromContainer($container),
        ];
    }

    protected function getConfig(): array
    {
        return [
            AppConfigKey::APP_BY_SCOPE => [
                Scope::HTTP->value => App::class,
            ],
            AppConfigKey::BOOT_TARGET_ADAPTERS => [
                HttpBootTargetAdapter::class,
            ],
            AppConfigKey::BOOTLOADERS => [
                HttpBootloader::class,
            ],
        ];
    }

    protected function getAutowires(): array
    {
        return [
            HttpBootloader::class,
            HttpBootTargetAdapter::class,
        ];
    }
}
