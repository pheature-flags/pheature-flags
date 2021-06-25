<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use Pheature\Crud\Toggle\Handler\SetStrategy;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class PatchFeatureFactory
{
    public function __invoke(ContainerInterface $container): PatchFeature
    {
        /** @var SetStrategy $addStrategy */
        $addStrategy = $container->get(SetStrategy::class);
        /** @var RemoveStrategy $removeStrategy */
        $removeStrategy = $container->get(RemoveStrategy::class);
        /** @var EnableFeature $enableFeature */
        $enableFeature = $container->get(EnableFeature::class);
        /** @var DisableFeature $disableFeature */
        $disableFeature = $container->get(DisableFeature::class);
        /** @var ResponseFactoryInterface $responseFactory */
        $responseFactory = $container->get(ResponseFactoryInterface::class);

        return self::create($addStrategy, $removeStrategy, $enableFeature, $disableFeature, $responseFactory);
    }

    public static function create(
        SetStrategy $addStrategy,
        RemoveStrategy $removeStrategy,
        EnableFeature $enableFeature,
        DisableFeature $disableFeature,
        ResponseFactoryInterface $responseFactory
    ): PatchFeature {
        return new PatchFeature($addStrategy, $removeStrategy, $enableFeature, $disableFeature, $responseFactory);
    }
}
