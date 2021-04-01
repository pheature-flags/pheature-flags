<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle;

use Pheature\Crud\Toggle\Model\Feature;
use Pheature\Crud\Toggle\Model\FeatureId;

interface FeatureRepository
{
    public function save(Feature $feature): void;
    public function get(FeatureId $featureId): Feature;
}
