<?php

declare(strict_types=1);

namespace Pheature\Test\Dbal\Toggle\Container;

use Pheature\Dbal\Toggle\Cli\InitSchema;
use Pheature\Dbal\Toggle\Container\ConfigProvider;
use Pheature\Dbal\Toggle\Container\InitSchemaFactory;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private const EXPECTED_CONFIG = [
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

    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $actual = (new ConfigProvider())->__invoke();

        self::assertSame(self::EXPECTED_CONFIG, $actual);
    }
}
