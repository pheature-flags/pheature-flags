<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle\Container;

use Pheature\Dbal\Toggle\Cli\InitSchema;

final class ConfigProvider
{
    /**
     * @return array<string, array<string, array<string, string>>>
     */
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
