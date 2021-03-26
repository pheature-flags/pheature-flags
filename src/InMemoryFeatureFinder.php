<?php

declare(strict_types=1);

namespace Pheature\InMemory\Toggle;

use Pheature\Core\Toggle\Feature;
use Pheature\Core\Toggle\FeatureFinder;

class InMemoryFeatureFinder implements FeatureFinder
{
    public function get(string $featureId): Feature
    {
        // TODO: Implement get() method.
    }
}