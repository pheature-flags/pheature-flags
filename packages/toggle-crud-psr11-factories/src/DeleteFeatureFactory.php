<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class DeleteFeatureFactory
{
    public function __invoke(ContainerInterface $container): DeleteFeature
    {
        return new DeleteFeature(
            $container->get(RemoveFeature::class),
            $container->get(ResponseFactoryInterface::class)
        );
    }
}
