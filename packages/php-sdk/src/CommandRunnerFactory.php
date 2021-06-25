<?php

declare(strict_types=1);

namespace Pheature\Sdk;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Sdk\CommandRunner;
use Psr\Container\ContainerInterface;

final class CommandRunnerFactory
{
    public function __invoke(ContainerInterface $container): CommandRunner
    {
        /** @var FeatureFinder $featureFinder */
        $featureFinder = $container->get(FeatureFinder::class);

        return new CommandRunner(
            new Toggle($featureFinder)
        );
    }
}
