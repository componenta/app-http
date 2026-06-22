<?php

declare(strict_types=1);

namespace Componenta\App\Server;

use Componenta\App\Boot\HttpBootloader;
use Componenta\App\Boot\HttpBootTargetAdapter;
use Componenta\App\ConfigKey as AppConfigKey;
use Componenta\Config\ConfigProvider as BaseConfigProvider;

final class ConfigProvider extends BaseConfigProvider
{
    protected function getConfig(): array
    {
        return [
            AppConfigKey::APP_ADAPTERS => [
                HttpAppAdapter::class,
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
            App::class,
            HttpAppAdapter::class,
            HttpBootloader::class,
            HttpBootTargetAdapter::class,
        ];
    }
}
