<?php

namespace Pheature\Test\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\CreateFeatureFactory;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

final class CreateFeatureFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfCreateFeature(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('get')
            ->with(FeatureRepository::class)
            ->willReturn($featureRepository);

        $addStrategyFactory = new CreateFeatureFactory();

        $addStrategyFactory->__invoke($container);
    }

    public function testItShouldCreateInstanceOfCreateFeatureStatically(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);

        $addStrategy = CreateFeatureFactory::create($featureRepository);

        $this->assertInstanceOf(CreateFeature::class, $addStrategy);
    }
}
