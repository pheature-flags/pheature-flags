<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Psr\Container\ContainerInterface;

final class EnableFeatureFactory
{
    public function __invoke(ContainerInterface $container): EnableFeature
    {
        /** @var FeatureRepository $featureRepository */
        $featureRepository = $container->get(FeatureRepository::class);

        return new EnableFeature($featureRepository);
    }
}
