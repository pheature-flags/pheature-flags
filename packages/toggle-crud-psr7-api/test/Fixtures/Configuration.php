<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr7\Toggle\Fixtures;

use Pheature\Crud\Psr11\Toggle\DeleteFeatureFactory;
use Pheature\Crud\Psr11\Toggle\GetFeatureFactory;
use Pheature\Crud\Psr11\Toggle\GetFeaturesFactory;
use Pheature\Crud\Psr11\Toggle\PatchFeatureFactory;
use Pheature\Crud\Psr11\Toggle\PostFeatureFactory;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PostFeature;

final class Configuration
{
    public static function create(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    GetFeatures::class => GetFeaturesFactory::class,
                    GetFeature::class => GetFeatureFactory::class,
                    PostFeature::class => PostFeatureFactory::class,
                    PatchFeature::class => PatchFeatureFactory::class,
                    DeleteFeature::class => DeleteFeatureFactory::class,
                ],
            ],
        ];
    }
}
