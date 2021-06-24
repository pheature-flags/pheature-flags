<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Psr\Container\ContainerInterface;

final class ToggleFactory
{
    public function __invoke(ContainerInterface $container): Toggle
    {
        /** @var FeatureFinder $featureFinder */
        $featureFinder = $container->get(FeatureFinder::class);

        return self::create($featureFinder);
    }

    public static function create(FeatureFinder $featureFinder): Toggle
    {
        return new Toggle($featureFinder);
    }
}
