<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Psr\Container\ContainerInterface;

final class EnableFeatureFactory
{
    public function __invoke(ContainerInterface $container): EnableFeature
    {
        /** @var FeatureRepository $featureRepository */
        $featureRepository = $container->get(FeatureRepository::class);

        return self::create($featureRepository);
    }

    public static function create(FeatureRepository $featureRepository): EnableFeature
    {
        return new EnableFeature($featureRepository);
    }
}
