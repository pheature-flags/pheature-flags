<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Dbal\Toggle\Write\DbalFeatureRepository;
use Pheature\InMemory\Toggle\InMemoryFeatureRepository;
use Psr\Container\ContainerInterface;

final class FeatureRepositoryFactory
{
    public function __invoke(ContainerInterface $container): FeatureRepository
    {
        /** @var ToggleConfig $config */
        $config = $container->get(ToggleConfig::class);
        /** @var ?Connection $connection */
        $connection = $container->get(Connection::class);

        return self::create($config, $connection);
    }

    public static function create(ToggleConfig $config, ?Connection $connection): FeatureRepository
    {
        $driver = $config->driver();

        if ('inmemory' === $driver) {
            return new InMemoryFeatureRepository();
        }

        if ('dbal' === $driver) {
            /** @var Connection $connection */
            return new DbalFeatureRepository($connection);
        }

        throw new InvalidArgumentException('Valid driver required');
    }
}
