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
    public static function create(CompilerPassInterface $compilerPass, string $driver = 'inmemory'): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $extension = new PheatureFlagsExtension();
        $containerBuilder->registerExtension($extension);

        $phpFileLoader = new YamlFileLoader($containerBuilder, new FileLocator());
        if ('inmemory' === $driver) {
            $phpFileLoader->load(realpath(__DIR__ . '/../../config/config.yaml'));
        }
        if ('dbal' === $driver) {
            $phpFileLoader->load(realpath(__DIR__ . '/../../config/dbal_config.yaml'));
        }
        $compilerPass->process($containerBuilder);

        return $containerBuilder;
    }
}