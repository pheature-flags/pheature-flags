<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\SetStrategy;
use Psr\Container\ContainerInterface;

final class SetStrategyFactory
{
    public function __invoke(ContainerInterface $container): SetStrategy
    {
        /** @var FeatureRepository $featureRepository */
        $featureRepository = $container->get(FeatureRepository::class);

        return self::create($featureRepository);
    }

    public static function create(FeatureRepository $featureRepository): SetStrategy
    {
        return new SetStrategy($featureRepository);
    }
}
