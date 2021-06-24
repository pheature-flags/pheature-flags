<?php

declare(strict_types=1);

namespace Pheature\Test\Dbal\Toggle\Fixtures;

use Pheature\Dbal\Toggle\Cli\InitSchema;
use Pheature\Dbal\Toggle\Container\InitSchemaFactory;

final class Configuration
{
    public static function create(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    InitSchema::class => InitSchemaFactory::class,
                ],
            ],
            'laminas-cli' => [
                'commands' => [
                    'pheature:dbal:init-toggle' => InitSchema::class,
                ],
            ],
        ];
    }
}
