<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr11\Toggle\Container;

use Pheature\Crud\Psr11\Toggle\Container\ConfigProvider;
use Pheature\Test\Crud\Psr11\Toggle\Fixtures\Configuration;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $expected = Configuration::create();
        $actual = (new ConfigProvider())->__invoke();

        self::assertSame($expected, $actual);
    }
}
