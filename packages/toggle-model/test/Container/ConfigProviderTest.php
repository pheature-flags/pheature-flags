<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle\Container;

use Pheature\Model\Toggle\Container\ConfigProvider;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private const EXPECTED_CONFIG = [
        'dependencies' => [
            'factories' => [
                StrategyFactory::class => \Pheature\Crud\Psr11\Toggle\StrategyFactory::class,
                SegmentFactory::class => \Pheature\Crud\Psr11\Toggle\SegmentFactory::class,
            ],
        ],
    ];

    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $actual = (new ConfigProvider())->__invoke();

        self::assertSame(self::EXPECTED_CONFIG, $actual);
    }
}
