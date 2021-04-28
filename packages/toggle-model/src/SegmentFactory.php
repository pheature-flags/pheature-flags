<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;
use Pheature\Core\Toggle\Read\Segment;
use Pheature\Core\Toggle\Read\SegmentFactory as ISegmentFactory;

final class SegmentFactory implements ISegmentFactory
{
    public function create(string $segmentId, string $segmentType, array $criteria): Segment
    {
        if (StrictMatchingSegment::NAME === $segmentType) {
            return new StrictMatchingSegment($segmentId, $criteria);
        }
        if (IdentitySegment::NAME === $segmentType) {
            return new IdentitySegment($segmentId, $criteria);
        }
        if (InCollectionMatchingSegment::NAME === $segmentType) {
            return new InCollectionMatchingSegment($segmentId, $criteria);
        }

        throw InvalidSegmentTypeGiven::withType($segmentType);
    }

    public function types(): array
    {
        return [
            StrictMatchingSegment::NAME,
            IdentitySegment::NAME,
            InCollectionMatchingSegment::NAME,
        ];
    }
}
