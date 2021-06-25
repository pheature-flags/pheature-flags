<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Container;

use Pheature\Core\Toggle\Container\ConfigProvider;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\ToggleFactory;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private const EXPECTED_CONFIG = [
        'dependencies' => [
            'factories' => [
                FeatureFinder::class => FeatureFinderFactory::class,
                Toggle::class => ToggleFactory::class,
                FeatureRepository::class => FeatureRepositoryFactory::class,
                ChainToggleStrategyFactory::class => \Pheature\Crud\Psr11\Toggle\ChainToggleStrategyFactory::class,
            ],
        ],
    ];

    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $actual = (new ConfigProvider())->__invoke();

        self::assertSame(self::EXPECTED_CONFIG, $actual);
    }
}
