<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle;

use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\StrictMatchingSegment;
use Pheature\Model\Toggle\SegmentFactory;
use PHPUnit\Framework\TestCase;

final class SegmentFactoryTest extends TestCase
{
    private const SEGMENT_ID = 'some_segment';
    private const STRICT_MATCHING_SEGMENT = 'strict_matching_segment';
    private const IDENTITY_SEGMENT = 'identity_segment';
    private const CRITERIA = [
        'some' => 'criteria',
        'with' => [
            'mixed' => 'values',
        ],
    ];

    public function testItShouldKnownAvailableSegmentTypes(): void
    {
        $factory = new SegmentFactory();

        self::assertSame([
            self::STRICT_MATCHING_SEGMENT,
            self::IDENTITY_SEGMENT,
        ], $factory->types());
    }

    public function testItShouldThrowAnExceptionWithUnknownStrategyTypes(): void
    {
        $this->expectException(InvalidSegmentTypeGiven::class);
        $factory = new SegmentFactory();

        $factory->create(self::SEGMENT_ID, 'unknown_strategy_type', self::CRITERIA);
    }

    public function testItShouldCreateInstancesOfStrictMatchingSegment(): void
    {
        $factory = new SegmentFactory();

        $segment = $factory->create(self::SEGMENT_ID, self::STRICT_MATCHING_SEGMENT, self::CRITERIA);
        self::assertInstanceOf(StrictMatchingSegment::class, $segment);
    }

    public function testItShouldCreateInstancesOfIdentitySegment(): void
    {
        $factory = new SegmentFactory();

        $segment = $factory->create(self::SEGMENT_ID, self::IDENTITY_SEGMENT, self::CRITERIA);
        self::assertInstanceOf(IdentitySegment::class, $segment);
    }
}
