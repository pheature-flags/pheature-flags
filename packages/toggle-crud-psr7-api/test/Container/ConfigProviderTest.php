<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr7\Toggle\Container;

use Pheature\Crud\Psr7\Toggle\DeleteFeatureFactory;
use Pheature\Crud\Psr7\Toggle\GetFeatureFactory;
use Pheature\Crud\Psr7\Toggle\GetFeaturesFactory;
use Pheature\Crud\Psr7\Toggle\PatchFeatureFactory;
use Pheature\Crud\Psr7\Toggle\PostFeatureFactory;
use Pheature\Crud\Psr7\Toggle\Container\ConfigProvider;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private const EXPECTED_CONFIG = [
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

    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $actual = (new ConfigProvider())->__invoke();

        self::assertSame(self::EXPECTED_CONFIG, $actual);
    }
}
