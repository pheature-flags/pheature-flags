<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Command;

use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\StrategyId;

final class RemoveStrategy
{
    private FeatureId $featureId;
    private StrategyId $strategyId;

    private function __construct(string $featureId, string $strategyId)
    {
        $this->featureId = FeatureId::fromString($featureId);
        $this->strategyId = StrategyId::fromString($strategyId);
    }

    public static function withFeatureAndStrategyId(string $featureId, string $strategyId): self
    {
        return new self($featureId, $strategyId);
    }

    public function featureId(): FeatureId
    {
        return $this->featureId;
    }

    public function strategyId(): StrategyId
    {
        return $this->strategyId;
    }
}
