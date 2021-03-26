<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle;

interface FeatureFinder
{
    public function get(string $featureId): Feature;
}
