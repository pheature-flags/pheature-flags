<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Symfony\DependencyInjection;

use Pheature\Community\Symfony\DependencyInjection\SegmentFactoryPass;
use Pheature\Core\Toggle\Read\ChainSegmentFactory;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\StrictMatchingSegment;
use PHPUnit\Framework\TestCase;

final class SegmentFactoryPassTest extends TestCase
{
    public function testItShouldRegisterToggleStrategyFactoryInContainer(): void
    {
        $compilerPass = new SegmentFactoryPass();
        $container = TestContainerFactory::create($compilerPass);

        $segmentFactoryDefinition = $container->getDefinition(SegmentFactory::class);
        self::assertFalse($segmentFactoryDefinition->isAutowired());
        self::assertTrue($segmentFactoryDefinition->isLazy());
        self::assertCount(2, $segmentFactoryDefinition->getArguments());

        $segment1 = $container->getDefinition(IdentitySegment::NAME);
        self::assertFalse($segment1->isAutowired());
        self::assertTrue($segment1->isLazy());
        $segment2 = $container->getDefinition(StrictMatchingSegment::NAME);
        self::assertFalse($segment2->isAutowired());
        self::assertTrue($segment2->isLazy());

        $segmentFactory = $container->get(SegmentFactory::class);
        self::assertInstanceOf(ChainSegmentFactory::class, $segmentFactory);
    }
}
