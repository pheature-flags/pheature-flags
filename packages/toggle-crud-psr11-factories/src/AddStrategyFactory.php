<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\AddStrategy;
use Psr\Container\ContainerInterface;

final class AddStrategyFactory
{
    public function __invoke(ContainerInterface $container): AddStrategy
    {
        return new AddStrategy(
            $container->get(FeatureRepository::class)
        );
    }
}
    