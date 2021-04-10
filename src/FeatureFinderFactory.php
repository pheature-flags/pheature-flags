<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Doctrine\DBAL\Connection;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Dbal\Toggle\Read\DbalFeatureFactory;
use Pheature\Dbal\Toggle\Read\DbalFeatureFinder;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
use Psr\Container\ContainerInterface;

final class FeatureFinderFactory
{
    public function __invoke(ContainerInterface $container): FeatureFinder
    {
        $config = $container->get('config');

        $driver = $config['pheature_flags']['driver'];

        if ('inmemory' === $driver) {
            return new InMemoryFeatureFinder(
                new InMemoryConfig($config['pheature_flags']['toggles']),
                new InMemoryFeatureFactory()
            );
        }

        if ('dbal' === $driver) {
            return new DbalFeatureFinder(
                $container->get(Connection::class),
                new DbalFeatureFactory()
            );
        }

        throw new \InvalidArgumentException('Valid driver required');
    }
}
    