<?php

declare(strict_types=1);

namespace Pheature\Community\Mezzio;

use Mezzio\Application;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\AddStrategyFactory;
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
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use Pheature\Crud\Toggle\Handler\AddStrategy;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Pheature\Sdk\CommandRunner;

final class ToggleConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'invokables' => [
                    RouterDelegator::class => RouterDelegator::class,
                ],
                'factories' => [
                    // Config
                    ToggleConfig::class => ToggleConfigFactory::class,
                    // SDK
                    CommandRunner::class => CommandRunnerFactory::class,
                    // CRUD
                    CreateFeature::class => CreateFeatureFactory::class,
                    AddStrategy::class => AddStrategyFactory::class,
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
                ],
                'delegators' => [
                    Application::class => [
                        RouterDelegator::class,
                    ],
                ],
            ],
            'pheature_flags' => [
                'route_prefix' => '',
                'driver' => 'inmemory',
                'toggles' => [
                    'feature_1' => [
                        'id' => 'feature_1',
                        'enabled' => true,
                        'strategies' => [
                            [
                                'segments' => [
                                    [
                                        'id' => 'location_barcelona',
                                        'criteria' => [
                                            'location' => 'barcelona',
                                        ]
                                    ],
                                    [
                                        'id' => 'location_bilbao',
                                        'criteria' => [
                                            'location' => 'bilbao',
                                        ]
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }
}