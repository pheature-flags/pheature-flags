<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use JsonSerializable;

final class Segment implements JsonSerializable
{
    private SegmentId $segmentId;
    private SegmentType $segmentType;
    private Payload $payload;

    public function __construct(SegmentId $segmentId, SegmentType $segmentType, Payload $payload)
    {
        $this->segmentId = $segmentId;
        $this->segmentType = $segmentType;
        $this->payload = $payload;
    }

    public function segmentId(): SegmentId
    {
        return $this->segmentId;
    }

    public function segmentType(): SegmentType
    {
        return $this->segmentType;
    }

    public function payload(): Payload
    {
        return $this->payload;
    }

    /**
     * @return array<string, string|array<string, mixed>>
     */
    public function jsonSerialize(): array
    {
        return [
            'segment_id' => $this->segmentId->value(),
            'segment_type' => $this->segmentType->value(),
            'payload' => $this->payload->criteria(),
        ];
    }
}
