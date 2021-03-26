<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle;

final class Toggle
{
    private FeatureFinder $featureFinder;

    public function __construct(FeatureFinder $featureRepository)
    {
        $this->featureFinder = $featureRepository;
    }

    public function isEnabled(string $featureId, ConsumerIdentity $identity): bool
    {
        $feature = $this->featureFinder->get($featureId);

        if (false === $feature->isEnabled()) {
            return false;
        }

        foreach ($feature->strategies() as $strategy) {
            if ($strategy->isSatisfiedBy($identity)) {
                return true;
            }
        }

        return false;
    }
}
