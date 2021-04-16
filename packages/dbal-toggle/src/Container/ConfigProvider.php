<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle\Container;

use Pheature\Dbal\Toggle\Cli\InitSchema;

final class ConfigProvider
{
    public function __invoke(): array
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
