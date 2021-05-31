<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Mezzio;

use Pheature\Community\Mezzio\ToggleConfigFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Test\Community\Mezzio\Fixtures\TestContainerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use UnexpectedValueException;

final class ToggleConfigFactoryTest extends TestCase
{
    public function testItShouldThrowAnExceptionWhenNoConfigurationFoundInContainer(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);
        $container = TestContainerFactory::create();

        (new ToggleConfigFactory())->__invoke($container);
    }

    public function testItShouldThrowAnExceptionWhenNoPheatureFlagsConfigurationFound(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $container = TestContainerFactory::createWithEmptyConfiguration();

        (new ToggleConfigFactory())->__invoke($container);
    }

    public function testItCreatesAToggleConfig(): void
    {
        $container = TestContainerFactory::createWithPheatureFlagsConfiguration();

        $actual = (new ToggleConfigFactory())->__invoke($container);

        self::assertInstanceOf(ToggleConfig::class, $actual);
    }
}
