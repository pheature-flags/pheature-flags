<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr11\Toggle\Container;

use Pheature\Community\Mezzio\ToggleConfigFactory;
use Pheature\Crud\Psr11\Toggle\Container\ConfigProvider;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private const EXPECTED_CONFIG = [
        'dependencies' => [
            'factories' => [
                ToggleConfig::class => ToggleConfigFactory::class,
            ],
        ],
    ];

    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $actual = (new ConfigProvider())->__invoke();

        self::assertSame(self::EXPECTED_CONFIG, $actual);
    }
}
