<?php

declare(strict_types=1);

namespace Pheature\InMemory\Toggle;

use InvalidArgumentException;

final class InMemoryFeatureNotFound extends InvalidArgumentException
{
    public static function withId(string $featureId): self
    {
        return new self(sprintf(
            'There is not feature with id %s configured.',
            $featureId
        ));
    }
}
