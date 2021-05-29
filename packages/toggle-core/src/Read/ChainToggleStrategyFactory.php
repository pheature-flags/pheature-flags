<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\InvalidStrategyTypeGiven;

use function array_map;
use function array_merge;
use function in_array;

final class ChainToggleStrategyFactory implements ToggleStrategyFactory
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

        return $this->create($strategyId, $strategyType, new Segments(...array_map(
            function (array $segment) {
                /** @var string $segmentId */
                $segmentId = $segment['segment_id'];
                /** @var string $segmentType */
                $segmentType = $segment['segment_type'];
                /** @var array<string, mixed> $criteria */
                $criteria = $segment['criteria'];

                return $this->segmentFactory->create($segmentId, $segmentType, $criteria);
            },
            $segments
        )));
    }

    public function create(string $strategyId, string $strategyType, ?Segments $segments = null): ToggleStrategy
    {
        foreach ($this->toggleStrategyFactories as $toggleStrategyFactory) {
            if (in_array($strategyType, $toggleStrategyFactory->types(), true)) {
                return $toggleStrategyFactory->create($strategyId, $strategyType, $segments);
            }
        }

        throw InvalidStrategyTypeGiven::withType($strategyType);
    }

    public function types(): array
    {
        return array_merge(
            ...array_map(
                static fn(ToggleStrategyFactory $strategyFactory) => $strategyFactory->types(),
                $this->toggleStrategyFactories
            )
        );
    }
}
