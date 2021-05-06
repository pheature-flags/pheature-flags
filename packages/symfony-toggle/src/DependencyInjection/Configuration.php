<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pheature_flags');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        /** @phpstan-ignore-next-line */
        $rootNode->children()
            ->enumNode('driver')
                ->values(['inmemory', 'dbal'])
            ->end()
            ->scalarNode('prefix')
                ->defaultValue('')
            ->end()
        ->end();

        $this->addStrategyTypes($rootNode);
        $this->addSegmentTypes($rootNode);
        $this->addToggles($rootNode);

        return $treeBuilder;
    }

    private function addStrategyTypes(ArrayNodeDefinition $rootNode): void
    {
        /** @phpstan-ignore-next-line */
        $rootNode
            ->children()
                ->arrayNode('strategy_types')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('type')->end()
                            ->scalarNode('factory_id')->end()
                        ->end()
                    ->end()
                ->end();
    }

    private function addSegmentTypes(ArrayNodeDefinition $rootNode): void
    {
        /** @phpstan-ignore-next-line */
        $rootNode
            ->children()
                ->arrayNode('segment_types')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('type')->end()
                            ->scalarNode('factory_id')->end()
                        ->end()
                    ->end()
                ->end();
    }

    private function addToggles(ArrayNodeDefinition $rootNode): void
    {
        /** @phpstan-ignore-next-line */
        $rootNode
            ->children()
                ->arrayNode('toggles')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('id')->end()
                            ->scalarNode('enabled')->end()
                            ->arrayNode('strategies')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('strategy_id')->end()
                                        ->scalarNode('strategy_type')->end()
                                        ->arrayNode('segments')
                                            ->arrayPrototype()
                                                ->children()
                                                    ->scalarNode('segment_id')->end()
                                                    ->scalarNode('segment_type')->end()
                                                    ->variableNode('criteria')->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
}
