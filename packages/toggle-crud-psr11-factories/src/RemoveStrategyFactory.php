<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Psr\Container\ContainerInterface;

final class RemoveStrategyFactory
{
    public function __invoke(ContainerInterface $container): RemoveStrategy
    {
        return new RemoveStrategy(
            $container->get(FeatureRepository::class)
        );
    }
}
    