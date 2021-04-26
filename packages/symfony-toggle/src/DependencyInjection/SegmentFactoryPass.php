<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\DependencyInjection;

use Pheature\Core\Toggle\Read\ChainSegmentFactory;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class SegmentFactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var array<array<mixed>> $pheatureFlagsConfig */
        $pheatureFlagsConfig = $container->getExtensionConfig('pheature_flags');
        $mergedConfig = array_merge(...$pheatureFlagsConfig);

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
    }
}
