<?php

declare(strict_types=1);

namespace Pheature\Test\Sdk\Fixtures;

use Pheature\Crud\Psr11\Toggle\CommandRunnerFactory;
use Pheature\Sdk\CommandRunner;

final class Configuration
{
    public static function create(): array
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
