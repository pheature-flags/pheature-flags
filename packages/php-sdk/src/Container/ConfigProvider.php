<?php

declare(strict_types=1);

namespace Pheature\Sdk\Container;

use Pheature\Sdk\CommandRunner;
use Pheature\Sdk\CommandRunnerFactory;

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
