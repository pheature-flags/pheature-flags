<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class GetFeatureFactory
{
    public function __invoke(ContainerInterface $container): GetFeature
    {
        /** @var FeatureFinder $featureFinder */
        $featureFinder = $container->get(FeatureFinder::class);
        /** @var ResponseFactoryInterface $responseFactory */
        $responseFactory = $container->get(ResponseFactoryInterface::class);

        return self::create($featureFinder, $responseFactory);
    }

    public static function create(FeatureFinder $featureFinder, ResponseFactoryInterface $responseFactory): GetFeature
    {
        return new GetFeature($featureFinder, $responseFactory);
    }
}
