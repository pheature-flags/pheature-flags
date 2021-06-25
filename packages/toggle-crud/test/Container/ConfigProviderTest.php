<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Container;

use Pheature\Crud\Toggle\Handler\CreateFeatureFactory;
use Pheature\Crud\Toggle\Handler\DisableFeatureFactory;
use Pheature\Crud\Toggle\Handler\EnableFeatureFactory;
use Pheature\Crud\Toggle\Handler\RemoveFeatureFactory;
use Pheature\Crud\Toggle\Handler\RemoveStrategyFactory;
use Pheature\Crud\Toggle\Handler\SetStrategyFactory;
use Pheature\Crud\Toggle\Container\ConfigProvider;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Pheature\Crud\Toggle\Handler\SetStrategy;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private const EXPECTED_CONFIG = [
        'dependencies' => [
            'factories' => [
                CreateFeature::class => CreateFeatureFactory::class,
                SetStrategy::class => SetStrategyFactory::class,
                RemoveStrategy::class => RemoveStrategyFactory::class,
                EnableFeature::class => EnableFeatureFactory::class,
                DisableFeature::class => DisableFeatureFactory::class,
                RemoveFeature::class => RemoveFeatureFactory::class,
            ],
        ],
    ];

    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $actual = (new ConfigProvider())->__invoke();

        self::assertSame(self::EXPECTED_CONFIG, $actual);
    }
}
