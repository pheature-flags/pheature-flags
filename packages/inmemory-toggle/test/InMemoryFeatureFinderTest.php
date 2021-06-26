<?php

declare(strict_types=1);

namespace Pheature\Test\InMemory\Toggle;

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\Segment;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Pheature\Core\Toggle\Read\ToggleStrategy;
use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
use Pheature\InMemory\Toggle\InMemoryFeatureNotFound;
use PHPUnit\Framework\TestCase;

final class InMemoryFeatureFinderTest extends TestCase
{
    private const CONFIG = [
        [
            'id' => 'some_feature',
            'enabled' => false,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_by_location',
                    'strategy_type' => 'enable_by_matching_segment',
                    'segments' => [
                        [
                            'segment_id' => 'requests_from_barcelona',
                            'segment_type' => 'strict_matching_segment',
                            'criteria' => ['location' => 'barcelona'],
                        ],
                    ],
                ],
            ]
        ]
    ];
    private ChainToggleStrategyFactory $chainToggleStrategyFactory;
    private InMemoryConfig $config;

    private function setCaseDependencies(): void
    {
        $segmentFactory = $this->createMock(SegmentFactory::class);
        $segmentFactory->expects(self::once())
            ->method('create')
            ->willReturn($this->createMock(Segment::class));
        $strategyFactory = $this->createMock(ToggleStrategyFactory::class);
        $strategyFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->createMock(ToggleStrategy::class));
        $strategyFactory->expects(self::once())
            ->method('types')
            ->willReturn(['enable_by_matching_segment']);
        $this->chainToggleStrategyFactory = new ChainToggleStrategyFactory($segmentFactory, $strategyFactory);
        $this->config = new InMemoryConfig(self::CONFIG);
    }

    public function testItShouldThrowExceptionWhenFeatureDoesntExistsAtInMemoryFeatureFinder(): void
    {
        $this->expectException(InMemoryFeatureNotFound::class);

        $segmentFactory = $this->createMock(SegmentFactory::class);
        $strategyFactory = $this->createMock(ToggleStrategyFactory::class);

        $chainToggleStrategyFactory = new ChainToggleStrategyFactory($segmentFactory, $strategyFactory);
        $config = new InMemoryConfig([]);  $featureFactory = new InMemoryFeatureFactory($chainToggleStrategyFactory);

        $finder = new InMemoryFeatureFinder($config, $featureFactory);
        $finder->get('some_unexistent_feature');
    }

    public function testItShouldGetAllFeaturesFromInMemoryFeatureFinder(): void
    {
        $this->setCaseDependencies();
        $featureFactory = new InMemoryFeatureFactory($this->chainToggleStrategyFactory);

        $finder = new InMemoryFeatureFinder($this->config, $featureFactory);
        self::assertCount(1, $finder->all());
    }

    public function testItShouldGetAFeatureByIdFromInMemoryFeatureFinder(): void
    {
        $this->setCaseDependencies();
        $featureFactory = new InMemoryFeatureFactory($this->chainToggleStrategyFactory);

        $finder = new InMemoryFeatureFinder($this->config, $featureFactory);
        $feature = $finder->get('some_feature');
        self::assertCount(1, $feature->strategies());
    }
}
