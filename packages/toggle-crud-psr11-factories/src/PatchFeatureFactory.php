<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Toggle\Handler\AddStrategy;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class PatchFeatureFactory
{
    public function __invoke(ContainerInterface $container): PatchFeature
    {
        return new PatchFeature(
            $container->get(AddStrategy::class),
            $container->get(RemoveStrategy::class),
            $container->get(EnableFeature::class),
            $container->get(DisableFeature::class),
            $container->get(ResponseFactoryInterface::class)
        );
    }
}
    