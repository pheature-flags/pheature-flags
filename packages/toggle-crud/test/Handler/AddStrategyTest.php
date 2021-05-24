<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Core\Toggle\Write\Segment;
use Pheature\Core\Toggle\Write\Strategy;
use Pheature\Crud\Toggle\Command\SetStrategy as AddStrategyCommand;
use Pheature\Crud\Toggle\Handler\SetStrategy;
use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use PHPUnit\Framework\TestCase;

final class AddStrategyTest extends TestCase
{
    private const FEATURE_ID = '252f6942-20ac-4b69-960a-d4246b1895c8';
    private const STRATEGY_ID = 'ec721d39-4665-48b8-8e6f-0de3af19cbaf';
    private const STRATEGY_TYPE = 'percentual';
    private const SEGMENTS = [
        [
            'segment_id' => 'some_segment',
            'segment_type' => 'some_segment_type',
            'criteria' => [
                'some_criteria' => 'some_value',
            ]
        ]
    ];

    public function testItShouldAddStrategyToAFeature(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::FEATURE_ID));
        $command = AddStrategyCommand::withIdTypeAndSegments(self::FEATURE_ID, self::STRATEGY_ID, self::STRATEGY_TYPE);
        $repository = $this->createMock(FeatureRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $repository->expects($this->once())
            ->method('save')
            ->with($feature);

        $handler = new SetStrategy($repository);
        $handler->handle($command);

        $strategies = $feature->strategies();
        $this->assertCount(1, $strategies);
    }

    public function testItShouldAddStrategyWithSegmentsToAFeature(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::FEATURE_ID));
        $command = AddStrategyCommand::withIdTypeAndSegments(
            self::FEATURE_ID,
            self::STRATEGY_ID,
            self::STRATEGY_TYPE,
            self::SEGMENTS
        );
        $repository = $this->createMock(FeatureRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $repository->expects($this->once())
            ->method('save')
            ->with($feature);

        $handler = new SetStrategy($repository);
        $handler->handle($command);

        $strategies = $feature->strategies();
        $this->assertCount(1, $strategies);
        /** @var Strategy $strategy */
        $strategy = $strategies[0];
        $this->assertCount(1, $strategy->segments());
    }
}
