<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Write;

use Pheature\Core\Toggle\Write\Payload;
use Pheature\Core\Toggle\Write\Segment;
use Pheature\Core\Toggle\Write\SegmentId;
use Pheature\Core\Toggle\Write\SegmentType;
use PHPUnit\Framework\TestCase;

final class SegmentTest extends TestCase
{
    private const SEGMENT_ID = 'some_segment_name';
    private const SEGMENT_TYPE = 'exact_match';
    private const JSON_PAYLOAD = '{"some":"segment"}';

    public function testItShouldBeCreatedWithExpectedValues(): void
    {
        $segment = new Segment(
            SegmentId::fromString(self::SEGMENT_ID),
            SegmentType::fromString(self::SEGMENT_TYPE),
            Payload::fromJsonString(self::JSON_PAYLOAD)
        );

        $this->assertSame([
            'segment_id' => self::SEGMENT_ID,
            'segment_type' => self::SEGMENT_TYPE,
            'criteria' => json_decode(self::JSON_PAYLOAD, true, 6, JSON_THROW_ON_ERROR),
        ], $segment->jsonSerialize());
        $this->assertSame(self::SEGMENT_ID, $segment->segmentId()->value());
        $this->assertSame(self::SEGMENT_TYPE, $segment->segmentType()->value());
        $this->assertSame(json_decode(self::JSON_PAYLOAD, true), $segment->payload()->criteria());
    }
}
