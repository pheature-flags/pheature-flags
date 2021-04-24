<?php

namespace Pheature\Test\Crud\Psr11\Toggle;

use Doctrine\DBAL\Connection;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Dbal\Toggle\Read\DbalFeatureFinder;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use InvalidArgumentException;

class FeatureFinderFactoryTest extends TestCase
{
    public function testItShouldThrowAnExceptionWhenItCantCreateAFeatureFinder(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $container = $this->createMock(ContainerInterface::class);
        $chainStrategyFactory = new ChainToggleStrategyFactory(
            $this->createMock(SegmentFactory::class),
            $this->createMock(ToggleStrategyFactory::class),
        );
        $toggleConfig = new ToggleConfig(['prefix' => '', 'driver' => 'some_other']);

        $container->expects(self::exactly(3))
            ->method('get')
            ->withConsecutive([ToggleConfig::class], [ChainToggleStrategyFactory::class], [Connection::class])
            ->willReturnOnConsecutiveCalls($toggleConfig, $chainStrategyFactory, null);

        $featureFinderFactory = new FeatureFinderFactory();

        $featureFinderFactory->__invoke($container);
    }


    public function testItShouldCreateADBalFeatureFinderFromInvokable(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $toggleConfig = new ToggleConfig(['prefix' => '', 'driver' => 'dbal']);
        $chainStrategyFactory = new ChainToggleStrategyFactory(
            $this->createMock(SegmentFactory::class),
            $this->createMock(ToggleStrategyFactory::class),
        );
        $connection = $this->createMock(Connection::class);

        $container->expects(self::exactly(3))
            ->method('get')
            ->withConsecutive([ToggleConfig::class], [ChainToggleStrategyFactory::class], [Connection::class])
            ->willReturnOnConsecutiveCalls($toggleConfig, $chainStrategyFactory, $connection);

        $featureFinderFactory = new FeatureFinderFactory();

        $finder = $featureFinderFactory->__invoke($container);
        self::assertInstanceOf(DbalFeatureFinder::class, $finder);
    }

    public function testItShouldCreateAnInMemoryFeatureFinderFromInvokable(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $toggleConfig = new ToggleConfig(['prefix' => '', 'driver' => 'inmemory']);
        $chainStrategyFactory = new ChainToggleStrategyFactory(
            $this->createMock(SegmentFactory::class),
            $this->createMock(ToggleStrategyFactory::class),
        );

        $container->expects(self::exactly(3))
            ->method('get')
            ->withConsecutive([ToggleConfig::class], [ChainToggleStrategyFactory::class], [Connection::class])
            ->willReturnOnConsecutiveCalls($toggleConfig, $chainStrategyFactory, null);

        $featureFinderFactory = new FeatureFinderFactory();

        $finder = $featureFinderFactory->__invoke($container);
        self::assertInstanceOf(InMemoryFeatureFinder::class, $finder);
    }

    public function testItShouldCreateADBalFeatureFinderFromCreate(): void
    {
        $toggleConfig = new ToggleConfig(['prefix' => '', 'driver' => 'dbal']);
        $chainStrategyFactory = new ChainToggleStrategyFactory(
            $this->createMock(SegmentFactory::class),
            $this->createMock(ToggleStrategyFactory::class),
        );
        $connection = $this->createMock(Connection::class);
        $featureFinder = FeatureFinderFactory::create($toggleConfig, $chainStrategyFactory, $connection);
        self::assertInstanceOf(FeatureFinder::class, $featureFinder);
    }

    public function testItShouldCreateInMemoryFeatureFinderFromCreate(): void
    {
        $toggleConfig = new ToggleConfig(['prefix' => '', 'driver' => 'inmemory']);
        $chainStrategyFactory = new ChainToggleStrategyFactory(
            $this->createMock(SegmentFactory::class),
            $this->createMock(ToggleStrategyFactory::class),
        );
        $connection = null;
        $featureFinder = FeatureFinderFactory::create($toggleConfig, $chainStrategyFactory, $connection);
        self::assertInstanceOf(FeatureFinder::class, $featureFinder);
    }

}
