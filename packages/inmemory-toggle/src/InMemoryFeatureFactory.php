<?php

declare(strict_types=1);

namespace Pheature\InMemory\Toggle;

use Pheature\Core\Toggle\Read\Feature as IFeature;
use Pheature\Core\Toggle\Read\Segments;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Core\Toggle\Read\ToggleStrategy;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\Feature;
use Pheature\Model\Toggle\Segment;

final class InMemoryFeatureFactory
{
    /**
     * @param array<string, mixed> $data
     * @return IFeature
     */
    public function create(array $data): IFeature
    {
        return new Feature(
            $data['id'],
            new ToggleStrategies(...array_map([$this, 'makeStrategy'], $data['strategies'])),
            $data['enabled']
        );
    }

    /**
     * @param array<string, mixed> $strategy
     * @return ToggleStrategy
     */
    private static function makeStrategy(array $strategy): ToggleStrategy
    {
        return new EnableByMatchingSegment(
            new Segments(
                ...array_map(
                    static fn(array $segment): Segment => new Segment($segment['id'], $segment['criteria']),
                    $strategy['segments']
                )
            )
        );
    }
}
