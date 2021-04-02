<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle;

use InvalidArgumentException;

final class DbalFeatureNotFound extends InvalidArgumentException
{
    public static function withId(string $featureId): self
    {
        return new self(sprintf(
            'There is not feature with id %s in database.',
            $featureId
        ));
    }
}
