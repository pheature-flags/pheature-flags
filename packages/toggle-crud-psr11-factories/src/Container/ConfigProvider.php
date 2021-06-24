<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle\Container;

use Pheature\Community\Mezzio\ToggleConfigFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;

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
                    ToggleConfig::class => ToggleConfigFactory::class,
                ],
            ],
        ];
    }
}
