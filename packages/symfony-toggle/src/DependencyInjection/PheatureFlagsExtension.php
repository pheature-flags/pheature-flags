<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\DependencyInjection;

use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Dbal\Toggle\Read\DbalFeatureFinder;
use Pheature\Dbal\Toggle\Write\DbalFeatureRepository;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class PheatureFlagsExtension extends ConfigurableExtension
{
    /**
     * @param array<mixed> $mergedConfig
     * @param ContainerBuilder $container
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $driver = $mergedConfig['driver'];

        $this->checkRequiredPackages($driver);

        $container->register(ToggleConfig::class, ToggleConfig::class)
            ->setAutowired(false)
            ->setLazy(true)
            ->addArgument($mergedConfig);

        $finder = $container->register(FeatureFinder::class, FeatureFinder::class)
            ->setAutowired(false)
            ->setLazy(true)
            ->setFactory([FeatureFinderFactory::class, 'create'])
            ->addArgument(new Reference(ToggleConfig::class))
            ->addArgument(new Reference(ChainToggleStrategyFactory::class));

        if ('dbal' === $driver) {
            $container->register(Connection::class, Connection::class)
                ->setAutowired(false)
                ->setLazy(true);
            $finder->addArgument(new Reference(Connection::class));
        }

        if ('inmemory' === $driver) {
            $finder->addArgument(null);
            $container->register(InMemoryFeatureFactory::class, InMemoryFeatureFactory::class)
                ->setAutowired(false)
                ->setLazy(true)
                ->addArgument(new Reference(ChainToggleStrategyFactory::class));
        }

        $container->register(Toggle::class, Toggle::class)
            ->setAutowired(false)
            ->setLazy(true)
            ->addArgument(
                new Reference(FeatureFinder::class)
            )
        ;
    }

    private function checkRequiredPackages(string $driver): void
    {
        switch ($driver) {
            case 'inmemory':
                if (!class_exists(InMemoryFeatureFactory::class, true)) {
                    throw new InvalidArgumentException('Run "composer require pheature/inmemory-toggle" to install InMemory feature storage.');
                }
                break;
            case 'dbal':
                if (!class_exists(DbalFeatureFinder::class, true)) {
                    throw new InvalidArgumentException('Run "composer require pheature/dbal-toggle" to install DBAL feature storage.');
                }
                break;
        }
    }
}
