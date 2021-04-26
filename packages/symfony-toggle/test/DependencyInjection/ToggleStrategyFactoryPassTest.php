<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Symfony\DependencyInjection;

use Pheature\Community\Symfony\DependencyInjection\ToggleStrategyFactoryPass;
use Pheature\Core\Toggle\Read\ChainSegmentFactory;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use PHPUnit\Framework\TestCase;

final class ToggleStrategyFactoryPassTest extends TestCase
{
    public function testItShouldRegisterToggleStrategyFactoryInContainer(): void
    {
        $compilerPass = new ToggleStrategyFactoryPass();
        $container = TestContainerFactory::create($compilerPass);
        $container->register(SegmentFactory::class, ChainSegmentFactory::class);

        $toggleStrategyFactoryDefinition = $container->getDefinition(ChainToggleStrategyFactory::class);
        self::assertFalse($toggleStrategyFactoryDefinition->isAutowired());
        self::assertTrue($toggleStrategyFactoryDefinition->isLazy());
        self::assertCount(3, $toggleStrategyFactoryDefinition->getArguments());

        $strategy1 = $container->getDefinition(EnableByMatchingIdentityId::NAME);
        self::assertFalse($strategy1->isAutowired());
        self::assertTrue($strategy1->isLazy());
        $strategy2 = $container->getDefinition(EnableByMatchingSegment::NAME);
        self::assertFalse($strategy2->isAutowired());
        self::assertTrue($strategy2->isLazy());

        $toggleStrategyFactory = $container->get(ChainToggleStrategyFactory::class);
        self::assertInstanceOf(ChainToggleStrategyFactory::class, $toggleStrategyFactory);
    }
}
