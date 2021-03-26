<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle;

use Pheature\Core\Toggle\Segment;
use Pheature\Core\Toggle\Segments;
use PHPUnit\Framework\TestCase;

final class SegmentsTest extends TestCase
{
    public function testItShouldGetSegments(): void
    {
        $segment1 = $this->createMock(Segment::class);
        $segment2 = $this->createMock(Segment::class);
        $segment3 = $this->createMock(Segment::class);
        $segments = new Segments(
            $segment1,
            $segment2,
            $segment3
        );

        $obtainedSegments = $segments->get();
        $this->assertSame($segment1, $obtainedSegments[0]);
        $this->assertSame($segment2, $obtainedSegments[1]);
        $this->assertSame($segment3, $obtainedSegments[2]);
    }
}
