<?php

declare(strict_types=1);

namespace Pheature\Test\Dbal\Toggle\Read;

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use Pheature\Dbal\Toggle\Read\DbalFeatureFactory;
use Pheature\Model\Toggle\Feature;
use PHPUnit\Framework\TestCase;
use function json_encode;

final class DbalFeatureFactoryTest extends TestCase
{
    /** @dataProvider getFeatureData */
    public function testCreateReadModelFeatureInstance(array $featureData): void
    {
        $strategyFactory = new ChainToggleStrategyFactory(
            $this->createMock(SegmentFactory::class),
            $this->createConfiguredMock(ToggleStrategyFactory::class, [
                'types' => ['enable_by_matching_segment']
            ]),
        );
        $factory = new DbalFeatureFactory($strategyFactory);
        $feature = $factory->create($featureData);
        self::assertInstanceOf(Feature::class, $feature);
    }

    public function getFeatureData(): array
    {
        return [
            'Feature without strategies' => [
                [
                    'feature_id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => '[]'
                ],
            ],
            'Feature with a single enable by matching segment strategy' => [
                [
                    'feature_id' => 'some_feature',
                    'enabled' => 1,
                    'strategies' => json_encode([
                        [
                            'strategy_id' => 'enable_for_developers',
                            'strategy_type' => 'enable_by_matching_segment',
                            'segments' => [
                                [
                                    'segment_id' => 'is_developer',
                                    'segment_type' => 'strict_matching_segment',
                                    'criteria' => [
                                        'developer',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ],
            ],
        ];
    }
}
