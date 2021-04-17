<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Read;

use Pheature\Core\Toggle\Read\Segment;
use Pheature\Core\Toggle\Read\Segments;
use PHPUnit\Framework\TestCase;

final class SegmentsTest extends TestCase
{
    public function testItShouldGetSegments(): void
    {
        $segment1 = $this->createConfiguredMock(Segment::class, ['id' => 'segment_1']);
        $segment2 = $this->createConfiguredMock(Segment::class, ['id' => 'segment_2']);
        $segment3 = $this->createConfiguredMock(Segment::class, ['id' => 'segment_3']);
        $segments = new Segments(
            $segment1,
            $segment2,
            $segment3
        );

        $obtainedSegments = $segments->all();
        $this->assertSame($segment1, $obtainedSegments[0]);
        $this->assertSame($segment2, $obtainedSegments[1]);
        $this->assertSame($segment3, $obtainedSegments[2]);
    }
}
