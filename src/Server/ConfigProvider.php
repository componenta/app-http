<?php

declare(strict_types=1);

namespace Componenta\App\Server;

use Componenta\App\Boot\HttpBootloader;
use Componenta\App\Boot\HttpBootTargetAdapter;
use Componenta\App\ConfigKey as AppConfigKey;
use Componenta\App\Scope;
use Componenta\Config\ContainerValue;
use LogicException;
use Componenta\Config\ConfigProvider as BaseConfigProvider;
use Psr\Container\ContainerInterface;

final class ConfigProvider extends BaseConfigProvider
{
    protected function getFactories(): array
    {
        return [
            App::class => static fn (ContainerInterface $container): App => App::createFromContainer($container),
            HttpApplicationInterface::class => static function (ContainerValue $container): HttpApplicationInterface {
                $id = $container->config->get(ConfigKey::RUNTIME, App::class);
                if (!is_string($id) || trim($id) === '' || $id === HttpApplicationInterface::class) {
                    throw new LogicException('HTTP runtime must identify a separate non-empty service ID.');
                }

                return $container->get($id, HttpApplicationInterface::class);
            },
        ];
    }

    protected function getConfig(): array
    {
        return [
            AppConfigKey::APPLICATIONS => [Scope::HTTP->value => HttpApplicationInterface::class],
            AppConfigKey::BOOT_TARGET_ADAPTERS => [
                HttpBootTargetAdapter::class,
            ],
            AppConfigKey::BOOTLOADERS => [
                HttpBootloader::class,
            ],
        ];
    }


}
