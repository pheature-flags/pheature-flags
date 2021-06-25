<?php

declare(strict_types=1);

namespace Pheature\Test\Sdk\Container;

use Pheature\Crud\Psr11\Toggle\CommandRunnerFactory;
use Pheature\Sdk\CommandRunner;
use Pheature\Sdk\Container\ConfigProvider;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private const EXPECTED_CONFIG = [
        'dependencies' => [
            'factories' => [
                CommandRunner::class => CommandRunnerFactory::class,
            ],
        ],
    ];

    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $actual = (new ConfigProvider())->__invoke();

        self::assertSame(self::EXPECTED_CONFIG, $actual);
    }
}
