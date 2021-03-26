<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle;

use Pheature\Core\Toggle\ToggleStrategies;
use Pheature\Core\Toggle\ToggleStrategy;
use PHPUnit\Framework\TestCase;

final class ToggleStrategiesTest extends TestCase
{
    public function testItShouldGetToggleStrategies(): void
    {
        $strategy1 = $this->createMock(ToggleStrategy::class);
        $strategy2 = $this->createMock(ToggleStrategy::class);
        $strategy3 = $this->createMock(ToggleStrategy::class);
        $toggleStrategies = new ToggleStrategies(
            $strategy1,
            $strategy2,
            $strategy3
        );

        $obtainedToggleStrategies = $toggleStrategies->get();
        $this->assertSame($strategy1, $obtainedToggleStrategies[0]);
        $this->assertSame($strategy2, $obtainedToggleStrategies[1]);
        $this->assertSame($strategy3, $obtainedToggleStrategies[2]);
    }
}
