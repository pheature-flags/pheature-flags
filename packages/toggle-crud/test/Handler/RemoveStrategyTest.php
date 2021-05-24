<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Core\Toggle\Write\Strategy;
use Pheature\Core\Toggle\Write\StrategyId;
use Pheature\Core\Toggle\Write\StrategyType;
use Pheature\Crud\Toggle\Command\RemoveStrategy as RemoveStrategyCommand;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use PHPUnit\Framework\TestCase;

final class RemoveStrategyTest extends TestCase
{
    private const FEATURE_ID = '252f6942-20ac-4b69-960a-d4246b1895c8';
    private const STRATEGY_ID = 'ec721d39-4665-48b8-8e6f-0de3af19cbaf';
    private const STRATEGY_TYPE = 'some_strategy';

    public function testItShouldAddStrategyToAFeature(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::FEATURE_ID));
        $feature->setStrategy(new Strategy(
            StrategyId::fromString(self::STRATEGY_ID),
            StrategyType::fromString(self::STRATEGY_TYPE)
        ));
        $command = RemoveStrategyCommand::withFeatureAndStrategyId(self::FEATURE_ID, self::STRATEGY_ID);
        $repository = $this->createMock(FeatureRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $repository->expects($this->once())
            ->method('save')
            ->with($feature);

        $this->assertCount(1, $feature->strategies());

        $handler = new RemoveStrategy($repository);
        $handler->handle($command);

        $this->assertCount(0, $feature->strategies());
    }
}
