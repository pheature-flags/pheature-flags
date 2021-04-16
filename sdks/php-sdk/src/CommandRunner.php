<?php

declare(strict_types=1);

namespace Pheature\Sdk;

use Pheature\Core\Toggle\Read\ConsumerIdentity;
use Pheature\Core\Toggle\Read\Toggle;

final class CommandRunner
{
    private Toggle $toggle;

    public function __construct(Toggle $toggle)
    {
        $this->toggle = $toggle;
    }

    public function inFeature(
        string $featureId,
        ConsumerIdentity $identity,
        ?OnEnabledFeature $onEnabledFeature = null,
        ?OnDisabledFeature $onDisabledFeature = null
    ): Result {
        $isEnabled = $this->toggle->isEnabled($featureId, $identity);

        if (true === $isEnabled && $onEnabledFeature) {
            $onEnabled = $onEnabledFeature->callback();
            return new Result($onEnabled(...$onEnabledFeature->arguments()));
        }

        if (false === $isEnabled && $onDisabledFeature) {
            $onDisabled = $onDisabledFeature->callback();
            return new Result($onDisabled(...$onDisabledFeature->arguments()));
        }

        return new Result();
    }
}
