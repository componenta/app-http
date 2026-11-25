<?php

declare(strict_types=1);

namespace Componenta\App\Server;

final class ConfigKey
{
    /** Optional service ID supplying the HTTP runtime instead of the default SAPI application. */
    public const string RUNTIME = 'app.http.runtime';

    private function __construct() {}
}
