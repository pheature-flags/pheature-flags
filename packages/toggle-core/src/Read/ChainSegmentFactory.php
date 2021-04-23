<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\InvalidSegmentTypeGiven;

final class ChainSegmentFactory
{
    /** @var SegmentFactory[] */
    private array $segmentFactories;

    public function __construct(SegmentFactory ...$segmentFactories)
    {
        $this->segmentFactories = $segmentFactories;
    }

    /**
     * @param string $segmentId
     * @param string $segmentType
     * @param array<string, mixed> $payload
     * @return Segment
     */
    public function create(string $segmentId, string $segmentType, array $payload): Segment
    {
        foreach ($this->segmentFactories as $segmentFactory) {
            if (in_array($segmentType, $segmentFactory->types(), true)) {
                return $segmentFactory->create($segmentId, $segmentType, $payload);
            }
        }

        throw InvalidSegmentTypeGiven::withType($segmentType);
    }
}
