<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

interface FeatureRepository
{
    public function save(Feature $feature): void;
    public function remove(FeatureId $featureId): void;
    public function get(FeatureId $featureId): Feature;
}
