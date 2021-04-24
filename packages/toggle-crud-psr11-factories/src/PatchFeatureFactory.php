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
        /** @var AddStrategy $addStrategy */
        $addStrategy = $container->get(AddStrategy::class);
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
        AddStrategy $addStrategy,
        RemoveStrategy $removeStrategy,
        EnableFeature $enableFeature,
        DisableFeature $disableFeature,
        ResponseFactoryInterface $responseFactory
    ): PatchFeature {
        return new PatchFeature($addStrategy, $removeStrategy, $enableFeature, $disableFeature, $responseFactory);
    }
}
