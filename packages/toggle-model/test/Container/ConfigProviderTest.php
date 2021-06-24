<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle\Container;

use Pheature\Model\Toggle\Container\ConfigProvider;
use Pheature\Test\Model\Toggle\Fixtures\Configuration;
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
