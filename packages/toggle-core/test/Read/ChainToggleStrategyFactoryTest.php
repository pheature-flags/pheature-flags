<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\InvalidStrategyTypeGiven;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\Segment;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Pheature\Core\Toggle\Read\ToggleStrategy;
use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use PHPUnit\Framework\TestCase;

final class ChainToggleStrategyFactoryTest extends TestCase
{
    private const STRATEGY_ID = 'some_strategy_id';
    private const STRATEGY_TYPE = 'some_type';
    private const SEGMENTS = [
        [
            'segment_id' => 'some_segment_id',
            'segment_type' => 'some_segment_type',
            'payload' => [
                'some' => 'data'
            ],
        ],
    ];

    public function testItShouldThrowAnExceptionWhenItCantCreateAToggleStrategyType(): void
    {
        $this->expectException(InvalidStrategyTypeGiven::class);
        $segmentFactory = $this->createMock(SegmentFactory::class);
        $toggleStrategyFactory = $this->createMock(ToggleStrategyFactory::class);
        $toggleStrategyFactory->expects(self::once())
            ->method('types')
            ->willReturn([]);
        $chainToggleStrategyFactory = new ChainToggleStrategyFactory($segmentFactory, $toggleStrategyFactory);

        $chainToggleStrategyFactory->createFromArray([
            'strategy_id' => self::STRATEGY_ID,
            'strategy_type' => self::STRATEGY_TYPE,
            'segments' => []
        ]);
    }

    public function testItShouldBeCreatedWithAtLeastOneToggleStrategyFactoryInstance(): void
    {
        $segmentFactory = $this->createMock(SegmentFactory::class);
        $toggleStrategyFactory = $this->createMock(ToggleStrategyFactory::class);
        $toggleStrategyFactory->expects(self::once())
            ->method('types')
            ->willReturn([self::STRATEGY_TYPE]);
        $expectedStrategy = $this->createMock(ToggleStrategy::class);
        $toggleStrategyFactory->expects(self::once())
            ->method('create')
            ->with(self::STRATEGY_ID, self::STRATEGY_TYPE)
            ->willReturn($expectedStrategy);
        $chainToggleStrategyFactory = new ChainToggleStrategyFactory($segmentFactory, $toggleStrategyFactory);

        $current = $chainToggleStrategyFactory->createFromArray([
            'strategy_id' => self::STRATEGY_ID,
            'strategy_type' => self::STRATEGY_TYPE,
            'segments' => []
        ]);

        self::assertSame($expectedStrategy, $current);
    }

    public function testItShouldBeCreatedWithAtLeastOneToggleStrategyFactoryInstanceAndSegments(): void
    {
        $segmentFactory = $this->createMock(SegmentFactory::class);
        $segmentFactory->expects(self::once())
            ->method('create')
            ->with(self::SEGMENTS[0]['segment_id'], self::SEGMENTS[0]['segment_type'],self::SEGMENTS[0]['payload'])
            ->willReturn($this->createMock(Segment::class));
        $toggleStrategyFactory = $this->createMock(ToggleStrategyFactory::class);
        $toggleStrategyFactory->expects(self::once())
            ->method('types')
            ->willReturn([self::STRATEGY_TYPE]);
        $expectedStrategy = $this->createMock(ToggleStrategy::class);
        $toggleStrategyFactory->expects(self::once())
            ->method('create')
            ->with(self::STRATEGY_ID, self::STRATEGY_TYPE)
            ->willReturn($expectedStrategy);
        $chainToggleStrategyFactory = new ChainToggleStrategyFactory($segmentFactory, $toggleStrategyFactory);

        $current = $chainToggleStrategyFactory->createFromArray([
            'strategy_id' => self::STRATEGY_ID,
            'strategy_type' => self::STRATEGY_TYPE,
            'segments' => self::SEGMENTS
        ]);

        self::assertSame($expectedStrategy, $current);
    }
}
    