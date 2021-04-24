<?php

namespace Pheature\Test\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Crud\Psr11\Toggle\GetFeaturesFactory;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class GetFeaturesFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfGetFeatures(): void
    {
        $featureFinder = $this->createMock(FeatureFinder::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $container = $this->createMock(ContainerInterface::class);

        $container->expects(static::exactly(2))
            ->method('get')
            ->withConsecutive([FeatureFinder::class], [ResponseFactoryInterface::class])
            ->willReturnOnConsecutiveCalls($featureFinder, $responseFactory);

        $getFeaturesFactory = new GetFeaturesFactory();

        $getFeatures = $getFeaturesFactory->__invoke($container);
        $this->assertInstanceOf(GetFeatures::class, $getFeatures);
    }

    public function testItShouldCreateInstanceOfGetFeaturesStatically(): void
    {
        $featureFinder = $this->createMock(FeatureFinder::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);

        $getFeatures = GetFeaturesFactory::create($featureFinder, $responseFactory);
        $this->assertInstanceOf(GetFeatures::class, $getFeatures);
    }
}
