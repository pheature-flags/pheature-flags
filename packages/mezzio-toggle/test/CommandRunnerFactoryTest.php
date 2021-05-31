<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Mezzio;

use Pheature\Community\Mezzio\CommandRunnerFactory;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Sdk\CommandRunner;
use Pheature\Test\Community\Mezzio\Fixtures\TestContainerFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;

final class CommandRunnerFactoryTest extends TestCase
{
    public function testItShouldThrowAnExceptionWhenFeatureFinderNotFoundInContainer(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $emptyContainer = TestContainerFactory::create();

        $commandRunnerFactory = new CommandRunnerFactory();
        $commandRunnerFactory->__invoke($emptyContainer);
    }

    public function testItShouldCreateACommandRunner(): void
    {
        /** @var FeatureFinder|MockObject $featureFinder */
        $featureFinder = $this->createMock(FeatureFinder::class);
        $container = TestContainerFactory::create([FeatureFinder::class => $featureFinder]);

        $commandRunnerFactory = new CommandRunnerFactory();
        $actual = $commandRunnerFactory->__invoke($container);

        self::assertInstanceOf(CommandRunner::class, $actual);
    }
}
