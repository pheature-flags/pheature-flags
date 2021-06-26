<?php

declare(strict_types=1);

use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\InCollectionMatchingSegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;

return [
    'api_prefix' => '',
    'api_enabled' => false,
    'driver' => 'inmemory',
    'segment_types' => [
        [
            'type' => IdentitySegment::NAME,
            'factory_id' => SegmentFactory::class
        ],
        [
            'type' => StrictMatchingSegment::NAME,
            'factory_id' => SegmentFactory::class
        ],
        [
            'type' => InCollectionMatchingSegment::NAME,
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
    'toggles' => [
        'feature_1' => [
            'id' => 'feature_1',
            'enabled' => false,
            'strategies' => [],
        ]
    ]
];
