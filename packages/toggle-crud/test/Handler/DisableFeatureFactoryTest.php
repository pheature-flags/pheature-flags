<?php

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\DisableFeatureFactory;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class DisableFeatureFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfCreateFeature(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('get')
            ->with(FeatureRepository::class)
            ->willReturn($featureRepository);

        $disableFeatureFactory = new DisableFeatureFactory();

        $disableFeatureFactory->__invoke($container);
    }

    public function testItShouldCreateInstanceOfCreateFeatureStatically(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);

        $addStrategy = DisableFeatureFactory::create($featureRepository);

        $this->assertInstanceOf(DisableFeature::class, $addStrategy);
    }
}
