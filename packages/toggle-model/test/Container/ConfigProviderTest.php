<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle\Container;

use Pheature\Model\Toggle\Container\ConfigProvider;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\SegmentFactoryFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrategyFactoryFactory;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private const EXPECTED_CONFIG = [
        'dependencies' => [
            'factories' => [
                StrategyFactory::class => StrategyFactoryFactory::class,
                SegmentFactory::class => SegmentFactoryFactory::class,
            ],
        ],
    ];

    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $actual = (new ConfigProvider())->__invoke();

        self::assertSame(self::EXPECTED_CONFIG, $actual);
    }
}
