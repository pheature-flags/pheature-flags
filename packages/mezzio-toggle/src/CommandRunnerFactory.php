<?php

declare(strict_types=1);

namespace Pheature\Community\Mezzio;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
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
