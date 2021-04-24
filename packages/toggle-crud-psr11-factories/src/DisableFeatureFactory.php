<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Psr\Container\ContainerInterface;

final class DisableFeatureFactory
{
    public function __invoke(ContainerInterface $container): DisableFeature
    {
        /** @var FeatureRepository $featureRepository */
        $featureRepository = $container->get(FeatureRepository::class);

        return self::create($featureRepository);
    }

    public static function create(FeatureRepository $featureRepository): DisableFeature
    {
        return new DisableFeature($featureRepository);
    }
}
