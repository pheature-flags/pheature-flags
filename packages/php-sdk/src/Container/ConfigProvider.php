<?php

declare(strict_types=1);

namespace Pheature\Sdk\Container;

use Pheature\Crud\Psr11\Toggle\CommandRunnerFactory;
use Pheature\Sdk\CommandRunner;

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
                    CommandRunner::class => CommandRunnerFactory::class,
                ],
            ],
        ];
    }
}
