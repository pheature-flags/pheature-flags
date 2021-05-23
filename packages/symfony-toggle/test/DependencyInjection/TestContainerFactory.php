<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Symfony\DependencyInjection;

use Pheature\Community\Symfony\DependencyInjection\PheatureFlagsExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class TestContainerFactory
{
    public static function create(CompilerPassInterface $compilerPass, ?string $driver = null, array $config = []): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $extension = new PheatureFlagsExtension();
        $containerBuilder->registerExtension($extension);

        if (!empty($config)) {
            $containerBuilder->loadFromExtension('pheature_flags', $config);

            $compilerPass->process($containerBuilder);

            return $containerBuilder;
        }

        $driver = $driver ?? 'inmemory';

        $yamlFileLoader = new YamlFileLoader($containerBuilder, new FileLocator());
        if ('inmemory' === $driver) {
            $yamlFileLoader->load(realpath(__DIR__ . '/../../config/config.yaml'));
        }
        if ('dbal' === $driver) {
            $yamlFileLoader->load(realpath(__DIR__ . '/../../config/dbal_config.yaml'));
        }
        $compilerPass->process($containerBuilder);

        return $containerBuilder;
    }
}
