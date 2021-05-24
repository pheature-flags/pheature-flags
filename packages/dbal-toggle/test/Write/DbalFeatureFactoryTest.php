<?php

declare(strict_types=1);

namespace Pheature\Test\Dbal\Toggle\Write;

use Generator;
use Pheature\Dbal\Toggle\Write\DbalFeatureFactory;
use PHPUnit\Framework\TestCase;

final class DbalFeatureFactoryTest extends TestCase
{
    /** @dataProvider getSomeFeatures */
    public function testItShouldCreateInstancesOfFeatureFromDatabaseRepresentation(array $featureData, int $expectedStrategies): void
    {
        $feature = DbalFeatureFactory::createFromDbalRepresentation($featureData);
        self::assertCount($expectedStrategies, $feature->strategies());
    }

    public function getSomeFeatures(): Generator
    {
        yield 'a feature with single strategy' => [
            [
                'feature_id' => 'some_feature',
                'enabled' => true,
                'strategies' => json_encode([
                    [
                        'strategy_id' => 'some_strategy',
                        'strategy_type' => 'some_type',
                        'segments' => [
                            [
                                'segment_id' => 'some_Segment',
                                'segment_type' => 'some_segment_type',
                                'criteria' => [
                                    'some' => 'value',
                                ]
                            ]
                        ],
                    ]
                ], JSON_THROW_ON_ERROR),
            ],
            1
        ];
        yield 'a feature without strategies' => [
            [
                'feature_id' => 'some_feature',
                'enabled' => true,
                'strategies' => json_encode([], JSON_THROW_ON_ERROR),
            ],
            0
        ];
        yield 'a feature with two strategies' => [
            [
                'feature_id' => 'some_feature',
                'enabled' => true,
                'strategies' => json_encode([
                    [
                        'strategy_id' => 'some_strategy',
                        'strategy_type' => 'some_type',
                        'segments' => [
                            [
                                'segment_id' => 'some_Segment',
                                'segment_type' => 'some_segment_type',
                                'criteria' => [
                                    'some' => 'value',
                                ]
                            ]
                        ],
                    ],
                    [
                        'strategy_id' => 'some_other_strategy',
                        'strategy_type' => 'some_type',
                        'segments' => [
                            [
                                'segment_id' => 'some_Segment',
                                'segment_type' => 'some_segment_type',
                                'criteria' => [
                                    'some' => 'value',
                                ]
                            ]
                        ],
                    ]
                ], JSON_THROW_ON_ERROR),
            ],
            2
        ];
    }
}
