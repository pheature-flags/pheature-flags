<?php

declare(strict_types=1);

namespace Pheature\Test\InMemory\Toggle;

use Generator;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\Segment;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Pheature\Core\Toggle\Read\ToggleStrategy;
use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\Model\Toggle\Feature;
use PHPUnit\Framework\TestCase;

final class InMemoryFeatureFactoryTest extends TestCase
{
    /** @dataProvider getFeatureData */
    public function testItShouldCreateNewFeatureInstancesFromInMemorySavedData(array $featureData): void
    {
        $segmentFactory = $this->createMock(SegmentFactory::class);
        $segmentFactory->expects(self::any())
            ->method('create')
            ->willReturn($this->createMock(Segment::class));
        $strategyFactory = $this->createMock(ToggleStrategyFactory::class);
        $strategyFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->createMock(ToggleStrategy::class));
        $strategyFactory->expects(self::any())
            ->method('types')
            ->willReturn(['enable_by_matching_segment']);

        $chainToggleStrategyFactory = new ChainToggleStrategyFactory($segmentFactory, $strategyFactory);

        $factory = new InMemoryFeatureFactory($chainToggleStrategyFactory);

        $feature = $factory->create($featureData);

        self::assertInstanceOf(Feature::class, $feature);
    }

    public function getFeatureData(): Generator
    {
        yield 'Minimum viable feature' => [
            [
                'id' => 'some_feature',
                'enabled' => false,
            ],
        ];

        yield 'A feature with single strategy and single segment' => [
            [
                'id' => 'some_feature',
                'enabled' => false,
                'strategies' => [
                    [
                        'strategy_id' => 'rollout_by_location',
                        'strategy_type' => 'enable_by_matching_segment',
                        'segments' => [
                            [
                                'segment_id' => 'requests_from_barcelona',
                                'segment_type' => 'strict_matching_segment',
                                'criteria' => ['location' => 'barcelona'],
                            ],
                        ],
                    ],
                ]
            ],
        ];
    }
}
