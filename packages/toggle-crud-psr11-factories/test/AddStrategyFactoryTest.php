<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\AddStrategyFactory;
use Pheature\Crud\Toggle\Handler\AddStrategy;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class AddStrategyFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfAddStrategy(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('get')
            ->with(FeatureRepository::class)
            ->willReturn($featureRepository);

        $addStrategyFactory = new AddStrategyFactory();

        $addStrategyFactory->__invoke($container);
    }

    public function testItShouldCreateInstanceOfAddStrategyStatically(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);

        $addStrategy = AddStrategyFactory::create($featureRepository);

        $this->assertInstanceOf(AddStrategy::class, $addStrategy);
    }
}
