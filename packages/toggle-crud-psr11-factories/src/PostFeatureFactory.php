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
        /** @var CreateFeature $createFeature */
        $createFeature = $container->get(CreateFeature::class);
        /** @var ResponseFactoryInterface $responseFactory */
        $responseFactory = $container->get(ResponseFactoryInterface::class);

        return self::create($createFeature, $responseFactory);
    }

    public static function create(CreateFeature $createFeature, ResponseFactoryInterface $responseFactory): PostFeature
    {
        return new PostFeature($createFeature, $responseFactory);
    }
}
