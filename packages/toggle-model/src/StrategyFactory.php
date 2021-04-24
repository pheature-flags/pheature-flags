<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Exception\InvalidStrategyTypeGiven;
use Pheature\Core\Toggle\Read\Segments;
use Pheature\Core\Toggle\Read\ToggleStrategy;
use Pheature\Core\Toggle\Read\ToggleStrategyFactory;

final class StrategyFactory implements ToggleStrategyFactory
{
    public function create(string $strategyId, string $strategyType, ?Segments $segments = null): ToggleStrategy
    {
        $segments = $segments ?? new Segments();
        if (EnableByMatchingSegment::NAME === $strategyType) {
            return new EnableByMatchingSegment($segments);
        }
        if (EnableByMatchingIdentityId::NAME === $strategyType) {
            return new EnableByMatchingIdentityId($segments);
        }

        throw InvalidStrategyTypeGiven::withType($strategyType);
    }

    public function types(): array
    {
        return [
            EnableByMatchingSegment::NAME,
            EnableByMatchingIdentityId::NAME,
        ];
    }
}
