<?php

declare(strict_types=1);

namespace Pheature\Test\Sdk\Container;

use Pheature\Sdk\Container\ConfigProvider;
use Pheature\Test\Sdk\Fixtures\Configuration;
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
