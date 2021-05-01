<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Exception;

use Exception;

class FeatureNotFoundInChainException extends Exception implements FeatureNotFoundException
{
    public static function withId(string $featureId): FeatureNotFoundException
    {
        return new self(
            sprintf('Feature with id %s not found in chain', $featureId)
        );
    }
}
