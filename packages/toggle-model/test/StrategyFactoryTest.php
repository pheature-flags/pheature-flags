<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle;

use Pheature\Core\Toggle\Exception\InvalidStrategyTypeGiven;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\StrategyFactory;
use PHPUnit\Framework\TestCase;

final class StrategyFactoryTest extends TestCase
{
    private const STRATEGY_ID = 'an_strategy_id';
    private const SEGMENT_MATCHING_STRATEGY_TYPE = 'enable_by_matching_segment';
    private const IDENTITY_MATCHING_STRATEGY_TYPE = 'enable_by_matching_identity_id';

    public function testItShouldKnownAvailableStrategyTypes(): void
    {
        $factory = new StrategyFactory();

        self::assertSame([
            self::SEGMENT_MATCHING_STRATEGY_TYPE,
            self::IDENTITY_MATCHING_STRATEGY_TYPE,
        ], $factory->types());
    }

    public function testItShouldThrowAnExceptionWithUnknownStrategyTypes(): void
    {
        $this->expectException(InvalidStrategyTypeGiven::class);
        $factory = new StrategyFactory();

        $factory->create(self::STRATEGY_ID, 'unknown_strategy_type');
    }

    public function testItShouldCreateInstancesOfEnableByMatchingSegmentStrategy(): void
    {
        $factory = new StrategyFactory();

        $strategy = $factory->create(self::STRATEGY_ID, self::SEGMENT_MATCHING_STRATEGY_TYPE);
        self::assertInstanceOf(EnableByMatchingSegment::class, $strategy);
    }

    public function testItShouldCreateInstancesOfEnableByMatchingIdentityStrategy(): void
    {
        $factory = new StrategyFactory();

        $strategy = $factory->create(self::STRATEGY_ID, self::IDENTITY_MATCHING_STRATEGY_TYPE);
        self::assertInstanceOf(EnableByMatchingIdentityId::class, $strategy);
    }
}
