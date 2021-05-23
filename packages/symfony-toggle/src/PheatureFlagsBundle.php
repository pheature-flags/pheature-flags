<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony;

use Pheature\Community\Symfony\DependencyInjection\FeatureRepositoryFactoryPass;
use Pheature\Community\Symfony\DependencyInjection\SegmentFactoryPass;
use Pheature\Community\Symfony\DependencyInjection\ToggleStrategyFactoryPass;
use Pheature\Community\Symfony\DependencyInjection\ToggleAPIPass;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PheatureFlagsBundle extends Bundle
{
    private const DEFAULT_CONFIG = [
        'driver' => 'inmemory',
        'api_prefix' => '',
        'api_enabled' => false,
        'segment_types' => [
            [
                'type' => IdentitySegment::NAME,
                'factory_id' => SegmentFactory::class
            ],
            [
                'type' => StrictMatchingSegment::NAME,
                'factory_id' => SegmentFactory::class
            ]
        ],
        'strategy_types' => [
            [
                'type' => EnableByMatchingSegment::NAME,
                'factory_id' => StrategyFactory::class
            ],
            [
                'type' => EnableByMatchingIdentityId::NAME,
                'factory_id' => StrategyFactory::class
            ]
        ],
        'toggles' => [],
    ];

    public function build(ContainerBuilder $container): void
    {
        $container->loadFromExtension('pheature_flags', self::DEFAULT_CONFIG);

        $container->addCompilerPass(new SegmentFactoryPass());
        $container->addCompilerPass(new ToggleStrategyFactoryPass());
        $container->addCompilerPass(new FeatureRepositoryFactoryPass());
        $container->addCompilerPass(new ToggleAPIPass());
    }
}
