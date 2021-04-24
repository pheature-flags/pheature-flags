<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;
use Pheature\Core\Toggle\Read\Segment as ISegment;
use Pheature\Core\Toggle\Read\SegmentFactory as ISegmentFactory;

final class SegmentFactory implements ISegmentFactory
{
    public function create(string $segmentId, string $segmentType, array $payload): ISegment
    {
        if (Segment::NAME === $segmentType) {
            return new Segment($segmentId, $payload);
        }
        if (IdentitySegment::NAME === $segmentType) {
            return new IdentitySegment($segmentId, $payload);
        }

        throw InvalidSegmentTypeGiven::withType($segmentType);
    }

    public function types(): array
    {
        return [
            Segment::NAME,
            IdentitySegment::NAME,
        ];
    }
}
