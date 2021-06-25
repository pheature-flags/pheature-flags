<?php

namespace Pheature\Test\Crud\Psr7\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr7\Toggle\DeleteFeatureFactory;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class DeleteFeatureFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfDeleteFeature(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $featureRepository = $this->createMock(FeatureRepository::class);

        $removeFeature = new RemoveFeature($featureRepository);

        $container->expects(static::exactly(2))
            ->method('get')
            ->withConsecutive([RemoveFeature::class], [ResponseFactoryInterface::class])
            ->willReturnOnConsecutiveCalls($removeFeature, $responseFactory);

        $deleteFeatureFactory = new DeleteFeatureFactory();

        $deleteFeatureFactory->__invoke($container);
    }

    public function testItShouldCreateInstanceOfDeleteFeatureStatically(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $removeFeature = new RemoveFeature($featureRepository);

        $createdInstance = DeleteFeatureFactory::create($removeFeature, $responseFactory);

        $this->assertInstanceOf(DeleteFeature::class, $createdInstance);
    }
}
