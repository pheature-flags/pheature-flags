<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use Pheature\Model\Toggle\StrategyFactory as ModelStrategyFactory;
use Psr\Container\ContainerInterface;

final class StrategyFactory
{
    public function __invoke(ContainerInterface $container): ToggleStrategyFactory
    {
        return new ModelStrategyFactory();
    }
}
