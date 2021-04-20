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
        /** @var RemoveFeature $removeFeature */
        $removeFeature = $container->get(RemoveFeature::class);
        /** @var ResponseFactoryInterface $responseFactory */
        $responseFactory = $container->get(ResponseFactoryInterface::class);

        return new DeleteFeature($removeFeature, $responseFactory);
    }
}
