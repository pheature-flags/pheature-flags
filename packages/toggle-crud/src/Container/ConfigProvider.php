<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Container;

use Pheature\Crud\Toggle\Handler\CreateFeatureFactory;
use Pheature\Crud\Toggle\Handler\DisableFeatureFactory;
use Pheature\Crud\Toggle\Handler\EnableFeatureFactory;
use Pheature\Crud\Toggle\Handler\RemoveFeatureFactory;
use Pheature\Crud\Toggle\Handler\RemoveStrategyFactory;
use Pheature\Crud\Toggle\Handler\SetStrategyFactory;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Pheature\Crud\Toggle\Handler\SetStrategy;

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
                    CreateFeature::class => CreateFeatureFactory::class,
                    SetStrategy::class => SetStrategyFactory::class,
                    RemoveStrategy::class => RemoveStrategyFactory::class,
                    EnableFeature::class => EnableFeatureFactory::class,
                    DisableFeature::class => DisableFeatureFactory::class,
                    RemoveFeature::class => RemoveFeatureFactory::class,
                ],
            ],
        ];
    }
}
