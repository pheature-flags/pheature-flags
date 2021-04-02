<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

interface StrategyFactory
{
    public function makeFromType(StrategyId $strategyId, StrategyType $strategyType): Strategy;
}
