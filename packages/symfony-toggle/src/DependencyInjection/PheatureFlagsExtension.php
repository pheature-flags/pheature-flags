<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\DependencyInjection;

use Doctrine\DBAL\Connection;
use Pheature\Core\Toggle\Read\ChainSegmentFactory;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\SegmentFactory;
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

        $repository = $container->register(FeatureRepository::class, FeatureRepository::class)
            ->setAutowired(false)
            ->setLazy(true)
            ->setFactory([FeatureRepositoryFactory::class, 'create']);

        if ('dbal' === $driver) {
            $finder->addArgument(new Reference(Connection::class));
            $repository->addArgument(new Reference(Connection::class));
        }

        if ('inmemory' === $driver) {
            $finder->addArgument(null);
            $repository->addArgument(null);
            $container->register(InMemoryFeatureFactory::class, InMemoryFeatureFactory::class)
                ->setAutowired(false)
                ->setLazy(true)
                ->addArgument(new Reference(ChainToggleStrategyFactory::class));
        }

        $segmentFactory = $container->register(SegmentFactory::class, SegmentFactory::class)
            ->setAutowired(false)
            ->setLazy(true)
            ->setClass(ChainSegmentFactory::class);

        foreach ($mergedConfig['segment_types'] as $segmentDefinition) {
            $container->register($segmentDefinition['type'], $segmentDefinition['factory_id'])
                ->setAutowired(false)
                ->setLazy(true);

            $segmentFactory->addArgument(new Reference($segmentDefinition['type']));
        }

        $container->register(Toggle::class, Toggle::class)
            ->setAutowired(false)
            ->setLazy(true)
            ->addArgument(
                new Reference(FeatureFinder::class)
            )
        ;
    }
}
