<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Command;

use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\StrategyId;
use Pheature\Core\Toggle\Write\StrategyType;

final class AddStrategy
{
    private FeatureId $featureId;
    private StrategyId $strategyId;
    private StrategyType $strategyType;

    private function __construct(string $featureId, string $strategyId, string $strategyType)
    {
        $this->featureId = FeatureId::fromString($featureId);
        $this->strategyId = StrategyId::fromString($strategyId);
        $this->strategyType = StrategyType::fromString($strategyType);
    }

    public static function withIdAndType(string $featureId, string $strategyId, string $strategyType): self
    {
        return new self($featureId, $strategyId, $strategyType);
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
}
