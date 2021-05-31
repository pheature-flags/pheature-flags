<?php

declare(strict_types=1);

namespace Pheature\Community\Mezzio;

use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Psr\Container\ContainerInterface;
use UnexpectedValueException;

final class ToggleConfigFactory
{
    public function __invoke(ContainerInterface $container): ToggleConfig
    {
        /** @var array<string, mixed> $config */
        $config = $container->get('config');

        if (empty($config['pheature_flags'])) {
            throw new UnexpectedValueException('"pheature_flags" configuration not found in container');
        }

        /** @var array<string, mixed> $pheatureConfig */
        $pheatureConfig = $config['pheature_flags'];

        return new ToggleConfig($pheatureConfig);
    }
}
