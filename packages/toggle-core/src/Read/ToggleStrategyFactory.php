<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

interface ToggleStrategyFactory extends AvailableTypes
{
    public function create(string $strategyId, string $strategyType, ?Segments $segments = null): ToggleStrategy;
}
