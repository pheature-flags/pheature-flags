<?php

namespace Pheature\Test\Crud\Psr11\Toggle;

use Doctrine\DBAL\Connection;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\FeatureFinderFactory;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Dbal\Toggle\Read\DbalFeatureFinder;
use Pheature\Dbal\Toggle\Write\DbalFeatureRepository;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
use Pheature\InMemory\Toggle\InMemoryFeatureRepository;
use Psr\Container\ContainerInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FeatureRepositoryFactoryTest extends TestCase
{
    public function testItShouldThrowAnExceptionWhenItCantCreateAFeatureRepository(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $container = $this->createMock(ContainerInterface::class);
        $toggleConfig = new ToggleConfig(['api_prefix' => '', 'driver' => 'some_other']);

        $container->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive([ToggleConfig::class], [Connection::class])
            ->willReturnOnConsecutiveCalls($toggleConfig, null);

        $featureRepositoryFactory = new FeatureRepositoryFactory();

        $featureRepositoryFactory->__invoke($container);
    }

    public function testItShouldCreateADBalFeatureRepositoryFromInvokable(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $toggleConfig = new ToggleConfig(['api_prefix' => '', 'driver' => 'dbal']);
        $connection = $this->createMock(Connection::class);

        $container->expects(static::exactly(2))
            ->method('get')
            ->withConsecutive([ToggleConfig::class], [Connection::class])
            ->willReturnOnConsecutiveCalls($toggleConfig, $connection);

        $featureRepositoryFactory = new FeatureRepositoryFactory();

        $repository = $featureRepositoryFactory->__invoke($container);
        self::assertInstanceOf(DbalFeatureRepository::class, $repository);
    }

    public function testItShouldCreateAnInMemoryFeatureRepositoryFromInvokable(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $toggleConfig = new ToggleConfig(['api_prefix' => '', 'driver' => 'inmemory']);

        $container->expects(static::exactly(2))
            ->method('get')
            ->withConsecutive([ToggleConfig::class], [Connection::class])
            ->willReturnOnConsecutiveCalls($toggleConfig, null);

        $featureRepositoryFactory = new FeatureRepositoryFactory();

        $repository = $featureRepositoryFactory->__invoke($container);
        self::assertInstanceOf(InMemoryFeatureRepository::class, $repository);
    }

    public function testItShouldCreateADBalFeatureRepositoryFromCreate(): void
    {
        $toggleConfig = new ToggleConfig(['api_prefix' => '', 'driver' => 'dbal']);
        $connection = $this->createMock(Connection::class);
        $featureRepository = FeatureRepositoryFactory::create($toggleConfig, $connection);
        self::assertInstanceOf(FeatureRepository::class, $featureRepository);
    }

    public function testItShouldCreateInMemoryFeatureRepositoryFromCreate(): void
    {
        $toggleConfig = new ToggleConfig(['api_prefix' => '', 'driver' => 'inmemory']);
        $connection = null;
        $featureRepository = FeatureRepositoryFactory::create($toggleConfig, $connection);
        self::assertInstanceOf(FeatureRepository::class, $featureRepository);
    }
}
