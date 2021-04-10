<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use InvalidArgumentException;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\InMemory\Toggle\InMemoryFeatureRepository;
use Psr\Container\ContainerInterface;

final class FeatureRepositoryFactory
{
    public function __invoke(ContainerInterface $container): FeatureRepository
    {
        $config = $container->get('config');

        if ('inmemory' === $config['pheature_flags']['driver']) {
            return new InMemoryFeatureRepository(
            );
        }

        throw new InvalidArgumentException('Valid driver required');
    }
}
    