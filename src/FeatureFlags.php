<?php

declare(strict_types=1);

namespace Pheature\Sdk;

use Pheature\Core\Toggle\Read\ConsumerIdentity;
use Pheature\Core\Toggle\Read\Toggle;

final class FeatureFlags
{
    private static Toggle $toggle;

    public static function setFacade(Toggle $toggle): void
    {
        self::$toggle = $toggle;
    }

    private static function getFacadeRoot(): CommandRunner
    {
        return new CommandRunner(self::$toggle);
    }

    public static function inFeature(
        string $featureId,
        ConsumerIdentity $identity,
        ?OnEnabledFeature $onEnabledFeature = null,
        ?OnDisabledFeature $onDisabledFeature = null
    ): Result {
        $instance = self::getFacadeRoot();

        return $instance->inFeature($featureId, $identity, $onEnabledFeature, $onDisabledFeature);
    }
}
