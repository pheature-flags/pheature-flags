<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr11\Toggle\Fixtures;

use Pheature\Crud\Psr11\Toggle\ToggleConfig;

final class TestToggleConfigFactory
{
    public static function create($config = []): ToggleConfig
    {
        return new ToggleConfig($config);
    }
}
