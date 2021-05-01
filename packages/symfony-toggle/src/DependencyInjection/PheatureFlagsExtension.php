<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\DependencyInjection;

use Doctrine\DBAL\Connection;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
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
            $finder->addArgument(new Reference(Connection::class));
        }

        if ('inmemory' === $driver) {
            $finder->addArgument(null);
            $container->register(InMemoryFeatureFactory::class, InMemoryFeatureFactory::class)
                ->setAutowired(false)
                ->setLazy(true)
                ->addArgument(new Reference(ChainToggleStrategyFactory::class));
        }

        $container->addCompilerPass(new SegmentFactoryPass());
        $container->addCompilerPass(new ToggleStrategyFactoryPass());
        $container->addCompilerPass(new FeatureRepositoryFactoryPass());

        $container->register(Toggle::class, Toggle::class)
            ->setAutowired(false)
            ->setLazy(true)
            ->addArgument(
                new Reference(FeatureFinder::class)
            )
        ;
    }
}
