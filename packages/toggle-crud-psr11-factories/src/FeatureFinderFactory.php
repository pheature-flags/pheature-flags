<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
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
        /** @var ToggleConfig $config */
        $config = $container->get(ToggleConfig::class);
        /** @var ChainToggleStrategyFactory $chainToggleStrategyFactory */
        $chainToggleStrategyFactory = $container->get(ChainToggleStrategyFactory::class);
        /** @var ?Connection $connection */
        $connection = $container->get(Connection::class);

        return self::create($config, $chainToggleStrategyFactory, $connection);
    }

    public static function create(
        ToggleConfig $config,
        ChainToggleStrategyFactory $chainToggleStrategyFactory,
        ?Connection $connection
    ): FeatureFinder {

        $driver = $config->driver();

        if ('inmemory' === $driver) {
            return new InMemoryFeatureFinder(
                new InMemoryConfig($config->toggles()),
                new InMemoryFeatureFactory(
                    $chainToggleStrategyFactory
                )
            );
        }

        if ('dbal' === $driver) {
            /** @var Connection $connection */
            return new DbalFeatureFinder($connection, new DbalFeatureFactory());
        }

        throw new InvalidArgumentException('Valid driver required');
    }
}
