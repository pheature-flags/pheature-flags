<?php

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\RemoveStrategyFactory;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class RemoveStrategyFactoryTest extends TestCase
{
    public function testItShouldCreateARemoveStrategyFromInvokable(): void
    {
        $mockedContainer = $this->createConfiguredMock(ContainerInterface::class, [
            'get' => $this->createMock(FeatureRepository::class)
        ]);

        $factory = new RemoveStrategyFactory();
        $actual = $factory->__invoke($mockedContainer);

        self::assertInstanceOf(RemoveStrategy::class, $actual);
    }

    public function testItShouldCreateARemoveStrategyFromCreate(): void
    {
        $actual = RemoveStrategyFactory::create($this->createMock(FeatureRepository::class));

        self::assertInstanceOf(RemoveStrategy::class, $actual);
    }
}
