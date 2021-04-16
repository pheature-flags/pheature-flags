<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class GetFeaturesFactory
{
    public function __invoke(ContainerInterface $container): GetFeatures
    {
        return new GetFeatures(
            $container->get(FeatureFinder::class),
            $container->get(ResponseFactoryInterface::class)
        );
    }
}
    