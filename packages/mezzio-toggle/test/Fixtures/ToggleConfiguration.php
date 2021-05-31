<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Mezzio\Fixtures;

use Mezzio\Application;
use Pheature\Community\Mezzio\CommandRunnerFactory;
use Pheature\Community\Mezzio\RouterDelegator;
use Pheature\Community\Mezzio\ToggleConfigFactory;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\CreateFeatureFactory;
use Pheature\Crud\Psr11\Toggle\DeleteFeatureFactory;
use Pheature\Crud\Psr11\Toggle\DisableFeatureFactory;
use Pheature\Crud\Psr11\Toggle\EnableFeatureFactory;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\GetFeatureFactory;
use Pheature\Crud\Psr11\Toggle\GetFeaturesFactory;
use Pheature\Crud\Psr11\Toggle\PatchFeatureFactory;
use Pheature\Crud\Psr11\Toggle\PostFeatureFactory;
use Pheature\Crud\Psr11\Toggle\RemoveFeatureFactory;
use Pheature\Crud\Psr11\Toggle\RemoveStrategyFactory;
use Pheature\Crud\Psr11\Toggle\SetStrategyFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Pheature\Crud\Toggle\Handler\SetStrategy;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;
use Pheature\Sdk\CommandRunner;

final class ToggleConfiguration
{
    public static function create(): array
    {
        return [
            'dependencies' => [
                'invokables' => [
                    RouterDelegator::class => RouterDelegator::class,
                ],
                'aliases' => [
                    'enable_by_matching_segment' => StrategyFactory::class,
                    'enable_by_matching_identity_id' => StrategyFactory::class,
                ],
                'factories' => [
                    // Config
                    ToggleConfig::class => ToggleConfigFactory::class,
                    // SDK
                    CommandRunner::class => CommandRunnerFactory::class,
                    // CRUD
                    CreateFeature::class => CreateFeatureFactory::class,
                    SetStrategy::class => SetStrategyFactory::class,
                    RemoveStrategy::class => RemoveStrategyFactory::class,
                    EnableFeature::class => EnableFeatureFactory::class,
                    DisableFeature::class => DisableFeatureFactory::class,
                    RemoveFeature::class => RemoveFeatureFactory::class,
                    // CRUD API
                    GetFeatures::class => GetFeaturesFactory::class,
                    GetFeature::class => GetFeatureFactory::class,
                    PostFeature::class => PostFeatureFactory::class,
                    PatchFeature::class => PatchFeatureFactory::class,
                    DeleteFeature::class => DeleteFeatureFactory::class,
                    // Read model
                    FeatureFinder::class => FeatureFinderFactory::class,
                    // Write model
                    FeatureRepository::class => FeatureRepositoryFactory::class,

                    StrategyFactory::class => \Pheature\Crud\Psr11\Toggle\StrategyFactory::class,
                    SegmentFactory::class => \Pheature\Crud\Psr11\Toggle\SegmentFactory::class,
                    ChainToggleStrategyFactory::class => \Pheature\Crud\Psr11\Toggle\ChainToggleStrategyFactory::class,
                ],
                'delegators' => [
                    Application::class => [
                        RouterDelegator::class,
                    ],
                ],
            ],
            'pheature_flags' => [
                'api_enabled' => false,
                'api_prefix' => '',
                'driver' => ToggleConfig::DRIVER_IN_MEMORY,
                'segment_types' => [
                    [
                        'type' => IdentitySegment::NAME,
                        'factory_id' => SegmentFactory::class
                    ],
                    [
                        'type' => StrictMatchingSegment::NAME,
                        'factory_id' => SegmentFactory::class
                    ]
                ],
                'strategy_types' => [
                    [
                        'type' => EnableByMatchingSegment::NAME,
                        'factory_id' => StrategyFactory::class
                    ],
                    [
                        'type' => EnableByMatchingIdentityId::NAME,
                        'factory_id' => StrategyFactory::class
                    ],
                ],
                'toggles' => [],
            ],
        ];
    }
}
