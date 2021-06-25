<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Crud\Psr11\Toggle\ToggleFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class ToggleFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfToggleFactory(): void
    {
        $featureFinder = $this->createMock(FeatureFinder::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('get')
            ->with(FeatureFinder::class)
            ->willReturn($featureFinder);

        $toggleFactory = new ToggleFactory();

        $toggleFactory->__invoke($container);
    }
}
