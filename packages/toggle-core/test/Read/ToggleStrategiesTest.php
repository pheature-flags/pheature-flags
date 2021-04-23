<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Read;

use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Core\Toggle\Read\ToggleStrategy;
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

        $this->assertSame(3, $toggleStrategies->count());
        $obtainedToggleStrategies = $toggleStrategies->getIterator();
        $this->assertSame($strategy1, $obtainedToggleStrategies->current());
        $obtainedToggleStrategies->next();
        $this->assertSame($strategy2, $obtainedToggleStrategies->current());
        $obtainedToggleStrategies->next();
        $this->assertSame($strategy3, $obtainedToggleStrategies->current());
        $this->assertSame([[], [], []], $toggleStrategies->jsonSerialize());
    }
}
