<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

interface Strategy
{
    public function id(): StrategyId;
    public function type(): StrategyType;
}
