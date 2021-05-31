<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Mezzio;

use Pheature\Community\Mezzio\ToggleConfigProvider;
use Pheature\Test\Community\Mezzio\Fixtures\ToggleConfiguration;
use PHPUnit\Framework\TestCase;

final class ToggleConfigProviderTest extends TestCase
{
    public function testItShouldCreateTheCorrectConfiguration(): void
    {
        $expected = ToggleConfiguration::create();
        $actual = (new ToggleConfigProvider())->__invoke();

        self::assertSame($expected, $actual);
    }
}
