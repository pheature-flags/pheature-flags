<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use Psr\Container\ContainerInterface;

final class StrategyFactoryFactory
{
    public function __invoke(ContainerInterface $container): ToggleStrategyFactory
    {
        return new StrategyFactory();
    }
}
