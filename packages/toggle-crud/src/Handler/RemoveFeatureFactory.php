<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Psr\Container\ContainerInterface;

final class RemoveFeatureFactory
{
    public function __invoke(ContainerInterface $container): RemoveFeature
    {
        /** @var FeatureRepository $featureRepository */
        $featureRepository = $container->get(FeatureRepository::class);

        return self::create($featureRepository);
    }

    public static function create(FeatureRepository $featureRepository): RemoveFeature
    {
        return new RemoveFeature($featureRepository);
    }
}
