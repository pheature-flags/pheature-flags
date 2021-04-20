<?php

declare(strict_types=1);

namespace Pheature\InMemory\Toggle;

use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\FeatureRepository;

final class InMemoryFeatureRepository implements FeatureRepository
{
    /** @var Feature[] */
    private array $features = [];

    public function save(Feature $feature): void
    {
        $this->features[$feature->id()] = $feature;
    }

    public function remove(FeatureId $featureId): void
    {
        unset($this->features[$featureId->value()]);
    }

    public function get(FeatureId $featureId): Feature
    {
        if (!array_key_exists($featureId->value(), $this->features)) {
            throw InMemoryFeatureNotFound::withId($featureId->value());
        }

        return $this->features[$featureId->value()];
    }
}
