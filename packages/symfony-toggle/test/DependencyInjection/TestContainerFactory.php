<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Symfony\DependencyInjection;

use Pheature\Community\Symfony\DependencyInjection\PheatureFlagsExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class TestContainerFactory
{
    public static function create(CompilerPassInterface $compilerPass): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $extension = new PheatureFlagsExtension();
        $containerBuilder->registerExtension($extension);

        $phpFileLoader = new YamlFileLoader($containerBuilder, new FileLocator());
        $phpFileLoader->load(realpath(__DIR__ . '/../../config/config.yaml'));

        $compilerPass->process($containerBuilder);

        return $containerBuilder;
    }
}