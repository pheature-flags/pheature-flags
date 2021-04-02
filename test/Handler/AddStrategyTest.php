<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Core\Toggle\Write\StrategyFactory;
use Pheature\Core\Toggle\Write\StrategyId;
use Pheature\Core\Toggle\Write\StrategyType;
use Pheature\Crud\Toggle\Command\AddStrategy as AddStrategyCommand;
use Pheature\Crud\Toggle\Handler\AddStrategy;
use Pheature\Crud\Toggle\Model\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use PHPUnit\Framework\TestCase;

final class AddStrategyTest extends TestCase
{
    private const FEATURE_ID = '252f6942-20ac-4b69-960a-d4246b1895c8';
    private const STRATEGY_ID = 'ec721d39-4665-48b8-8e6f-0de3af19cbaf';
    private const STRATEGY_TYPE = 'percentual';

    public function testItShouldAddStrategyToAFeature(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::FEATURE_ID));
        $command = AddStrategyCommand::withIdAndType(self::FEATURE_ID, self::STRATEGY_ID, self::STRATEGY_TYPE);
        $repository = $this->createMock(FeatureRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $repository->expects($this->once())
            ->method('save')
            ->with($feature);
        $strategyFactory = $this->createMock(StrategyFactory::class);
        $strategyFactory->expects($this->once())
            ->method('makeFromType')
            ->with(
                $this->isInstanceOf(StrategyId::class),
                $this->isInstanceOf(StrategyType::class)
            );

        $handler = new AddStrategy($repository, $strategyFactory);
        $handler->handle($command);

        $strategies = $feature->strategies();
        $this->assertCount(1, $strategies);
    }
}
