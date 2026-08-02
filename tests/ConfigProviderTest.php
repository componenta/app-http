<?php

declare(strict_types=1);

use Componenta\App\Boot\HttpBootTargetAdapter;
use Componenta\App\Boot\HttpBootloader;
use Componenta\App\ConfigKey as AppConfigKey;
use Componenta\App\Scope;
use Componenta\App\Server\App;
use Componenta\App\Server\ConfigProvider;
use Componenta\Config\ConfigKey as DependencyConfigKey;

describe('http app config provider', function (): void {
    it('registers the HTTP application, boot target adapter and bootloader', function (): void {
        $config = (new ConfigProvider())();

        expect($config[AppConfigKey::APP_BY_SCOPE][Scope::HTTP->value])->toBe(App::class)
            ->and($config[DependencyConfigKey::DEPENDENCIES][DependencyConfigKey::FACTORIES])
            ->toHaveKey(App::class)
            ->and($config[AppConfigKey::BOOT_TARGET_ADAPTERS])->toContain(HttpBootTargetAdapter::class)
            ->and($config[AppConfigKey::BOOTLOADERS])->toContain(HttpBootloader::class);
    });
});
