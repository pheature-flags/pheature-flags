<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Exception;

use Throwable;

interface FeatureNotFoundException extends Throwable
{
    public static function withId(string $featureId): self;
}
