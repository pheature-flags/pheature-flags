<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle\Write;

use Pheature\Core\Toggle\Write\Feature as IFeature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\Payload;
use Pheature\Core\Toggle\Write\Segment;
use Pheature\Core\Toggle\Write\SegmentId;
use Pheature\Core\Toggle\Write\Strategy;
use Pheature\Core\Toggle\Write\StrategyId;
use Pheature\Core\Toggle\Write\StrategyType;
use Pheature\Crud\Toggle\Model\Feature;

final class DbalFeatureFactory
{
    /**
     * @param array<string, mixed> $featureData
     * @return IFeature
     */
    public static function createFromDbalRepresentation(array $featureData): IFeature
    {
        $strategies = array_map(function (array $strategy) {
            $segments = array_map(fn(array $segment) => new Segment(
                SegmentId::fromString($segment['segment_id']),
                Payload::fromJsonString($segment['payload'])
            ), $strategy['segments']);

            return new Strategy(
                StrategyId::fromString($strategy['strategy_id']),
                StrategyType::fromString($strategy['strategy_type']),
                $segments
            );
        }, $featureData['strategies']);

        return new Feature(
            FeatureId::fromString($featureData['feature_id']),
            (bool)$featureData['enabled'],
            $strategies
        );
    }
}
    