<?php

namespace Pheature\Test\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\PatchFeatureFactory;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Toggle\Handler\AddStrategy;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class PatchFeatureFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfPatchFeature(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
        $addStrategy = new AddStrategy($featureRepository);
        $removeStrategy = new RemoveStrategy($featureRepository);
        $enableFeature = new EnableFeature($featureRepository);
        $disableFeature = new DisableFeature($featureRepository);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $container = $this->createMock(ContainerInterface::class);

        $container->expects(static::exactly(5))
            ->method('get')
            ->withConsecutive([AddStrategy::class], [RemoveStrategy::class],
                [EnableFeature::class], [DisableFeature::class], [ResponseFactoryInterface::class])
            ->willReturnOnConsecutiveCalls($addStrategy, $removeStrategy,
                $enableFeature, $disableFeature, $responseFactory);

        $patchFeatureFactory = new PatchFeatureFactory();

        $patchFeatureFactory->__invoke($container);
    }

    public function testItShouldCreateInstanceOfPatchFeatureStatically(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
        $addStrategy = new AddStrategy($featureRepository);
        $removeStrategy = new RemoveStrategy($featureRepository);
        $enableFeature = new EnableFeature($featureRepository);
        $disableFeature = new DisableFeature($featureRepository);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);

        $patchFeature = PatchFeatureFactory::create($addStrategy, $removeStrategy, $enableFeature, $disableFeature, $responseFactory);

        $this->assertInstanceOf(PatchFeature::class, $patchFeature);
    }
}
