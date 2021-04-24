<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\InvalidStrategyTypeGiven;

final class ChainToggleStrategyFactory
{
    private SegmentFactory $segmentFactory;
    /** @var ToggleStrategyFactory[] */
    private array $toggleStrategyFactories;

    public function __construct(SegmentFactory $segmentFactory, ToggleStrategyFactory ...$toggleStrategyFactories)
    {
        $this->segmentFactory = $segmentFactory;
        $this->toggleStrategyFactories = $toggleStrategyFactories;
    }

    /**
     * @param array<string, mixed> $strategy
     * @return ToggleStrategy
     */
    public function createFromArray(array $strategy): ToggleStrategy
    {
        /** @var array<array<string, string|array<string, mixed>>> $segments */
        $segments = $strategy['segments'];
        /** @var string $strategyId */
        $strategyId = $strategy['strategy_id'];
        /** @var string $strategyType */
        $strategyType = $strategy['strategy_type'];

        foreach ($this->toggleStrategyFactories as $toggleStrategyFactory) {
            if (in_array($strategy['strategy_type'], $toggleStrategyFactory->types(), true)) {
                return $this->makeStrategy($toggleStrategyFactory, $strategyId, $strategyType, $segments);
            }
        }

        throw InvalidStrategyTypeGiven::withType($strategyType);
    }

    /**
     * @param ToggleStrategyFactory $toggleStrategyFactory
     * @param string $strategyId
     * @param string $strategyType
     * @param array<array<string, string|array<string, mixed>>> $segments
     * @return ToggleStrategy
     */
    private function makeStrategy(
        ToggleStrategyFactory $toggleStrategyFactory,
        string $strategyId,
        string $strategyType,
        array $segments
    ): ToggleStrategy {
        return $toggleStrategyFactory->create(
            $strategyId,
            $strategyType,
            new Segments(...array_map(function (array $segment) {
                /** @var string $segmentId */
                $segmentId = $segment['segment_id'];
                /** @var string $segmentType */
                $segmentType = $segment['segment_type'];
                /** @var array<string, mixed> $criteria */
                $criteria = $segment['criteria'];

                return $this->segmentFactory->create($segmentId, $segmentType, $criteria);
            }, $segments))
        );
    }
}
