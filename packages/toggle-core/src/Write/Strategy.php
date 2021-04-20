<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use JsonSerializable;

use function array_map;

final class Strategy implements JsonSerializable
{
    private StrategyId $strategyId;
    private StrategyType $strategyType;
    /**
     * @var Segment[]
     */
    private array $segments;

    /**
     * Strategy constructor.
     *
     * @param StrategyId   $strategyId
     * @param StrategyType $strategyType
     * @param Segment[]    $segments
     */
    public function __construct(StrategyId $strategyId, StrategyType $strategyType, array $segments = [])
    {
        $this->strategyId = $strategyId;
        $this->strategyType = $strategyType;
        $this->segments = $segments;
    }

    public function id(): StrategyId
    {
        return $this->strategyId;
    }

    public function type(): StrategyType
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

    /**
     * @phpstan-return           array<string, mixed>
     * @phpstan-ignore-next-line
     * @return                   array{
     *   strategy_id: string,
     *   strategy_type: string
     *   segments: array<array-key, array<string, array<string, mixed>|string>>,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'strategy_id' => $this->strategyId->value(),
            'strategy_type' => $this->strategyType->value(),
            'segments' => array_map(static fn(Segment $segment) => $segment->jsonSerialize(), $this->segments),
        ];
    }
}
