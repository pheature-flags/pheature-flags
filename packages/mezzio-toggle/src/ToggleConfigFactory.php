<?php

declare(strict_types=1);

namespace Pheature\Community\Mezzio;

use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Psr\Container\ContainerInterface;

final class ToggleConfigFactory
{
    public function __invoke(ContainerInterface $container): ToggleConfig
    {
        return new ToggleConfig(
            $container->get('config')['pheature_flags']
        );
    }
}
    