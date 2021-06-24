<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Container;

use Pheature\Core\Toggle\Container\ConfigProvider;
use Pheature\Test\Core\Toggle\Fixtures\Configuration;
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
