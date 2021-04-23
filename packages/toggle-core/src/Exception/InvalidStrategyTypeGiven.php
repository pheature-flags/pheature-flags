<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Exception;

use InvalidArgumentException;

final class InvalidStrategyTypeGiven extends InvalidArgumentException
{

    public static function withType(string $strategyType): self
    {
        return new self(sprintf('Unknown toggle strategy type %s given.', $strategyType));
    }
}
