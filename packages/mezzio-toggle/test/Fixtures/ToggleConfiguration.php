<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Mezzio\Fixtures;

use Mezzio\Application;
use Pheature\Community\Mezzio\RouterDelegator;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;

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
                    'identity_segment' => SegmentFactory::class,
                    'strict_matching_segment' => SegmentFactory::class,
                ],
                'factories' => [],
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
