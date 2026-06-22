<?php

declare(strict_types=1);

use Componenta\App\Boot\HttpBootTargetAdapter;
use Componenta\App\Boot\HttpBootloader;
use Componenta\App\ConfigKey as AppConfigKey;
use Componenta\App\Server\ConfigProvider;
use Componenta\App\Server\HttpAppAdapter;

describe('http app config provider', function (): void {
    it('registers http runtime adapters and bootloader', function (): void {
        $config = (new ConfigProvider())();

        expect($config[AppConfigKey::APP_ADAPTERS])->toContain(HttpAppAdapter::class)
            ->and($config[AppConfigKey::BOOT_TARGET_ADAPTERS])->toContain(HttpBootTargetAdapter::class)
            ->and($config[AppConfigKey::BOOTLOADERS])->toContain(HttpBootloader::class);
    });
});
