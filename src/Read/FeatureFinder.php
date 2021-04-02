<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

interface FeatureFinder
{
    public function get(string $featureId): Feature;
}
