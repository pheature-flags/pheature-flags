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
        return new CommandRunner(
            new Toggle(
                $container->get(FeatureFinder::class)
            )
        );
    }
}
