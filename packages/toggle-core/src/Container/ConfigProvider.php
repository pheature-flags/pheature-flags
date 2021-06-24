<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Container;

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\ToggleFactory;

final class ConfigProvider
{
    /**
     * @return array<string, array<string, array<string, string>>>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    FeatureFinder::class => FeatureFinderFactory::class,
                    Toggle::class => ToggleFactory::class,
                    FeatureRepository::class => FeatureRepositoryFactory::class,
                    ChainToggleStrategyFactory::class => \Pheature\Crud\Psr11\Toggle\ChainToggleStrategyFactory::class,
                ],
            ],
        ];
    }
}
