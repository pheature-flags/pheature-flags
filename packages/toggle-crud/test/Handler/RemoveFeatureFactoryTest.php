<?php
declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Handler\RemoveFeatureFactory;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class RemoveFeatureFactoryTest extends TestCase
{
    public function testItShouldCreateARemoveFeatureFromInvokable(): void
    {
        $mockedContainer = $this->createConfiguredMock(ContainerInterface::class, [
            'get' => $this->createMock(FeatureRepository::class)
        ]);

        $factory = new RemoveFeatureFactory();
        $actual = $factory->__invoke($mockedContainer);

        self::assertInstanceOf(RemoveFeature::class, $actual);
    }

    public function testItShouldCreateARemoveFeatureFromCreate(): void
    {
        $actual = RemoveFeatureFactory::create($this->createMock(FeatureRepository::class));

        self::assertInstanceOf(RemoveFeature::class, $actual);
    }
}
