<?php

namespace Pheature\Test\Crud\Psr7\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr7\Toggle\PostFeatureFactory;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class PostFeatureFactoryTest extends TestCase
{
    public function testItShouldCreateAPostFeatureFromInvokable(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $createFeature = new CreateFeature($this->createMock(FeatureRepository::class));
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);

        $container
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive([CreateFeature::class], [ResponseFactoryInterface::class])
            ->willReturnOnConsecutiveCalls($createFeature, $responseFactory);

        $factory = new PostFeatureFactory();
        $actual = $factory->__invoke($container);

        self::assertInstanceOf(PostFeature::class, $actual);
    }

    public function testItShouldCreateAPostFeatureFromCreate(): void
    {
        $createFeature = new CreateFeature($this->createMock(FeatureRepository::class));
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);

        $actual = PostFeatureFactory::create(
            $createFeature,
            $responseFactory
        );

        self::assertInstanceOf(PostFeature::class, $actual);
    }
}
