<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle\Read;

use Pheature\Core\Toggle\Read\Feature as IFeature;
use Pheature\Core\Toggle\Read\Segments;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Core\Toggle\Read\ToggleStrategy;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\Feature;
use Pheature\Model\Toggle\StrictMatchingSegment;

final class DbalFeatureFactory
{
    /**
     * @param array<string, string|bool|array<string, mixed>> $data
     * @return IFeature
     */
    public function create(array $data): IFeature
    {
        /** @var string $id */
        $id = $data['id'];
        $enabled = (bool)$data['enabled'];
        /** @var array<string, array<string, mixed>> $strategies */
        $strategies = $data['strategies'];
        return new Feature(
            $id,
            /** @param array<string, array<string, mixed>> $strategies */
            new ToggleStrategies(...array_map([$this, 'makeStrategy'], $strategies)),
            $enabled
        );
    }

    /**
     * @param array<string, mixed> $strategy
     * @return ToggleStrategy
     */
    private static function makeStrategy(array $strategy): ToggleStrategy
    {
        /** @var array<array<string, mixed>> $segments */
        $segments = $strategy['segments'];

        return new EnableByMatchingSegment(
            new Segments(
                ...array_map(
                /** @param array<string, mixed> $segment */
                    static function (array $segment): StrictMatchingSegment {
                        /** @var string $id */
                        $id = $segment['id'];
                        /** @var array<string, mixed> $criteria */
                        $criteria = $segment['criteria'];
                        return new StrictMatchingSegment($id, $criteria);
                    },
                    $segments
                )
            )
        );
    }
}
