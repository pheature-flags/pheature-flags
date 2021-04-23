<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Exception;

use InvalidArgumentException;

final class InvalidSegmentTypeGiven extends InvalidArgumentException
{
    public static function withType(string $segmentType): self
    {
        return new self(sprintf('Unknown toggle strategy type %s given.', $segmentType));
    }
}
