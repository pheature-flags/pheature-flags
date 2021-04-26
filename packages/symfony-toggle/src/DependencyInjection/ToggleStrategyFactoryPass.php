<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\DependencyInjection;

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ToggleStrategyFactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var array<array<mixed>> $pheatureFlagsConfig */
        $pheatureFlagsConfig = $container->getExtensionConfig('pheature_flags');
        $mergedConfig = array_merge(...$pheatureFlagsConfig);

        $toggleStrategyFactory = $container->register(
            ChainToggleStrategyFactory::class,
            ChainToggleStrategyFactory::class
        )
            ->setAutowired(false)
            ->setLazy(true)
            ->addArgument(new Reference(SegmentFactory::class));

        /** @var array<string, string> $strategyDefinition */
        foreach ($mergedConfig['strategy_types'] as $strategyDefinition) {
            $container->register($strategyDefinition['type'], $strategyDefinition['factory_id'])
                ->setAutowired(false)
                ->setLazy(true);

            $toggleStrategyFactory->addArgument(new Reference($strategyDefinition['type']));
        }
    }
}
