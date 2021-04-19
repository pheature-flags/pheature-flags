<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\FeatureNotFoundException;

interface FeatureFinder
{
    /**
     * @param  string $featureId
     * @return Feature
     * @throws FeatureNotFoundException
     */
    public function get(string $featureId): Feature;

    /**
     * @return Feature[]
     */
    public function all(): array;
}
