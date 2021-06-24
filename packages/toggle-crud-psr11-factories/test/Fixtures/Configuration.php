<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr11\Toggle\Fixtures;

use Pheature\Community\Mezzio\ToggleConfigFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;

final class Configuration
{
    public static function create(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    ToggleConfig::class => ToggleConfigFactory::class,
                ],
            ],
        ];
    }
}
