<?php

declare(strict_types=1);

namespace Pheature\Community\Mezzio;

use Mezzio\Application;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\SetStrategyFactory;
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
use Pheature\Crud\Psr11\Toggle\ToggleFactory;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use Pheature\Crud\Toggle\Handler\SetStrategy;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;
use Pheature\Sdk\CommandRunner;

use function array_merge;
use function array_reduce;

final class ToggleConfigProvider
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        $pheatureFlagsConfig = $this->pheatureFlagsConfig();

        /** @var array<array<string, string>> $strategyTypes */
        $strategyTypes = $pheatureFlagsConfig['strategy_types'];

        $strategyTypeAliases = array_reduce(
            $strategyTypes,
            static function (array $strategies, array $current) {
                $strategies[(string) $current['type']] = (string) $current['factory_id'];

                return $strategies;
            },
            []
        );

        /** @var array<array<string, string>> $segmentTypes */
        $segmentTypes = $pheatureFlagsConfig['segment_types'];

        $segmentTypeAliases = array_reduce(
            $segmentTypes,
            static function (array $segments, array $current) {
                $segments[(string) $current['type']] = (string) $current['factory_id'];

                return $segments;
            },
            []
        );

        return [
            'dependencies' => [
                'invokables' => [
                    RouterDelegator::class => RouterDelegator::class,
                ],
                'aliases' => array_merge($strategyTypeAliases, $segmentTypeAliases),
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
                    Toggle::class => ToggleFactory::class,
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
            'pheature_flags' => $pheatureFlagsConfig,
        ];
    }

    /** @return array<string,mixed> */
    private function pheatureFlagsConfig(): array
    {
        return [
            'api_enabled' => false,
            'api_prefix' => '',
            'driver' => 'inmemory',
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
        ];
    }
}
