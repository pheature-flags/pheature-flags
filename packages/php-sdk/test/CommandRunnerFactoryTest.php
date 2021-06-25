<?php

declare(strict_types=1);

namespace Pheature\Test\Sdk;

use Exception;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Sdk\CommandRunner;
use Pheature\Sdk\CommandRunnerFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class CommandRunnerFactoryTest extends TestCase
{
    public function testItShouldThrowAnExceptionWhenFeatureFinderNotFoundInContainer(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $notFoundException = new class() extends Exception implements NotFoundExceptionInterface {
        };

        /** @var ContainerInterface|MockObject $emptyContainer */
        $emptyContainer = $this->createMock(ContainerInterface::class);
        $emptyContainer
            ->expects($this->once())
            ->method('get')
            ->with(FeatureFinder::class)
            ->willThrowException($notFoundException);

        $commandRunnerFactory = new CommandRunnerFactory();
        $commandRunnerFactory->__invoke($emptyContainer);
    }

    public function testItShouldCreateACommandRunner(): void
    {
        /** @var FeatureFinder|MockObject $featureFinder */
        $featureFinder = $this->createMock(FeatureFinder::class);

        /** @var ContainerInterface|MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('get')
            ->with(FeatureFinder::class)
            ->willReturn($featureFinder);

        $commandRunnerFactory = new CommandRunnerFactory();
        $actual = $commandRunnerFactory->__invoke($container);

        self::assertInstanceOf(CommandRunner::class, $actual);
    }
}
