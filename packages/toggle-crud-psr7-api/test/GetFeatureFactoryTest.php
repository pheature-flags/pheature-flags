<?php

namespace Pheature\Test\Crud\Psr7\Toggle;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Crud\Psr7\Toggle\GetFeatureFactory;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class GetFeatureFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfGetFeature(): void
    {
        $featureFinder = $this->createMock(FeatureFinder::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $container = $this->createMock(ContainerInterface::class);

        $container->expects(static::exactly(2))
            ->method('get')
            ->withConsecutive([FeatureFinder::class], [ResponseFactoryInterface::class])
            ->willReturnOnConsecutiveCalls($featureFinder, $responseFactory);

        $getFeatureFactory = new GetFeatureFactory();

        $getFeature = $getFeatureFactory->__invoke($container);
        $this->assertInstanceOf(GetFeature::class, $getFeature);
    }

    public function testItShouldCreateInstanceOfGetFeatureStatically(): void
    {
        $featureFinder = $this->createMock(FeatureFinder::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);

        $getFeature = GetFeatureFactory::create($featureFinder, $responseFactory);
        $this->assertInstanceOf(GetFeature::class, $getFeature);
    }
}
