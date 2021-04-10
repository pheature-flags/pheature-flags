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
        $config = $container->get('config');

        $driver = $config['pheature_flags']['driver'];

        if ('inmemory' === $driver) {
            return new InMemoryFeatureRepository(
            );
        }

        if ('dbal' === $driver) {
            return new DbalFeatureRepository(
                $container->get(Connection::class)
            );
        }

        throw new InvalidArgumentException('Valid driver required');
    }
}
    