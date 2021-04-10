<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
use Psr\Container\ContainerInterface;

final class FeatureFinderFactory
{
    public function __invoke(ContainerInterface $container): FeatureFinder
    {
        $config = $container->get('config');

        if ('inmemory' === $config['pheature_flags']['driver']) {
            return new InMemoryFeatureFinder(
                new InMemoryConfig($config['pheature_flags']['toggles']),
                new InMemoryFeatureFactory()
            );
        }

        throw new \InvalidArgumentException('Valid driver required');
    }
}
    