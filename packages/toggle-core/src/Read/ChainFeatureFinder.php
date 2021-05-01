<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use Pheature\Core\Toggle\Exception\FeatureNotFoundException;
use Pheature\Core\Toggle\Exception\FeatureNotFoundInChainException;

class ChainFeatureFinder implements FeatureFinder
{
    /** @var FeatureFinder[] */
    private array $featureFinders;

    public function __construct(FeatureFinder ...$featureFinders)
    {
        $this->featureFinders = $featureFinders;
    }

    public function get(string $featureId): Feature
    {
        foreach ($this->featureFinders as $featureFinder) {
            try {
                return $featureFinder->get($featureId);
            } catch (FeatureNotFoundException $exception) {
            }
        }

        throw FeatureNotFoundInChainException::withId($featureId);
    }

    public function all(): array
    {
        $features = [];

        foreach ($this->featureFinders as $featureFinder) {
            $features = array_merge($features, $featureFinder->all());
        }

        return $features;
    }
}
