<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Fixtures;

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\CreateFeatureFactory;
use Pheature\Crud\Psr11\Toggle\DisableFeatureFactory;
use Pheature\Crud\Psr11\Toggle\EnableFeatureFactory;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\RemoveFeatureFactory;
use Pheature\Crud\Psr11\Toggle\RemoveStrategyFactory;
use Pheature\Crud\Psr11\Toggle\SetStrategyFactory;
use Pheature\Crud\Psr11\Toggle\ToggleFactory;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Pheature\Crud\Toggle\Handler\SetStrategy;

final class Configuration
{
    public static function create(): array
    {
        return [
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
    }
}
