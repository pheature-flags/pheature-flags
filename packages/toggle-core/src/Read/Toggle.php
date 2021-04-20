<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

final class Toggle
{
    private const ZERO = 0;
    private FeatureFinder $featureFinder;

    public function __construct(FeatureFinder $featureRepository)
    {
        $this->featureFinder = $featureRepository;
    }

    public function isEnabled(string $featureId, ?ConsumerIdentity $identity = null): bool
    {
        $feature = $this->featureFinder->get($featureId);

        if (false === $feature->isEnabled()) {
            return false;
        }

        $strategies = $feature->strategies();

        if (self::ZERO === $strategies->count() || null === $identity) {
            return true;
        }

        foreach ($strategies as $strategy) {
            if ($strategy->isSatisfiedBy($identity)) {
                return true;
            }
        }

        return false;
    }
}
