<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;
use Pheature\Core\Toggle\Read\Segment as ISegment;
use Pheature\Core\Toggle\Read\SegmentFactory as ISegmentFactory;

final class SegmentFactory implements ISegmentFactory
{
    public function create(string $segmentId, string $segmentType, array $criteria): ISegment
    {
        if (StrictMatchingSegment::NAME === $segmentType) {
            return new StrictMatchingSegment($segmentId, $criteria);
        }
        if (IdentitySegment::NAME === $segmentType) {
            return new IdentitySegment($segmentId, $criteria);
        }

        throw InvalidSegmentTypeGiven::withType($segmentType);
    }

    public function types(): array
    {
        return [
            StrictMatchingSegment::NAME,
            IdentitySegment::NAME,
        ];
    }
}
