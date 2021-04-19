<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Crud\Psr7\Toggle\PostFeature;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class PostFeatureFactory
{
    public function __invoke(ContainerInterface $container): PostFeature
    {
        return new PostFeature(
            $container->get(CreateFeature::class),
            $container->get(ResponseFactoryInterface::class)
        );
    }
}
