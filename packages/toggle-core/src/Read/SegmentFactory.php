<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

interface SegmentFactory extends WithProcessableFixedTypes
{
    /**
     * @param string $segmentId
     * @param string $segmentType
     * @param array<string, mixed> $criteria
     * @return Segment
     */
    public function create(string $segmentId, string $segmentType, array $criteria): Segment;
}
