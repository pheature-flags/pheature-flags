<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Psr\Container\ContainerInterface;

final class CreateFeatureFactory
{
    public function __invoke(ContainerInterface $container): CreateFeature
    {
        return new CreateFeature(
            $container->get(FeatureRepository::class)
        );
    }
}
