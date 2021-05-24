<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Command;

use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\Payload;
use Pheature\Core\Toggle\Write\Segment;
use Pheature\Core\Toggle\Write\SegmentId;
use Pheature\Core\Toggle\Write\SegmentType;
use Pheature\Core\Toggle\Write\StrategyId;
use Pheature\Core\Toggle\Write\StrategyType;

final class SetStrategy
{
    private FeatureId $featureId;
    private StrategyId $strategyId;
    private StrategyType $strategyType;
    /** @var Segment[] */
    private array $segments;

    /**
     * AddStrategy constructor.
     * @param string $featureId
     * @param string $strategyId
     * @param string $strategyType
     * @param array<array<string, mixed>> $segments
     */
    private function __construct(string $featureId, string $strategyId, string $strategyType, array $segments)
    {
        $this->featureId = FeatureId::fromString($featureId);
        $this->strategyId = StrategyId::fromString($strategyId);
        $this->strategyType = StrategyType::fromString($strategyType);
        $this->segments = array_map(
            /** @param array<string, mixed> $segment */
            static function (array $segment) {
                /** @var array<string, mixed> $criteria */
                $criteria =  $segment['criteria'];

                return new Segment(
                    SegmentId::fromString((string)$segment['segment_id']),
                    SegmentType::fromString((string)$segment['segment_type']),
                    Payload::fromArray($criteria)
                );
            },
            $segments
        );
    }

    /**
     * @param string $featureId
     * @param string $strategyId
     * @param string $strategyType
     * @param array<array<string, mixed>> $segments
     * @return static
     */
    public static function withIdTypeAndSegments(
        string $featureId,
        string $strategyId,
        string $strategyType,
        array $segments = []
    ): self {
        return new self($featureId, $strategyId, $strategyType, $segments);
    }

    public function featureId(): FeatureId
    {
        return $this->featureId;
    }

    public function strategyId(): StrategyId
    {
        return $this->strategyId;
    }

    public function strategyType(): StrategyType
    {
        return $this->strategyType;
    }

    /**
     * @return Segment[]
     */
    public function segments(): array
    {
        return $this->segments;
    }
}
