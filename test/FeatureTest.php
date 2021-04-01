<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle;

use Pheature\Core\Toggle\ToggleStrategies;
use Pheature\Core\Toggle\ToggleStrategy;
use Pheature\Model\Toggle\Feature;
use PHPUnit\Framework\TestCase;

class FeatureTest extends TestCase
{
    private const FEATURE_ID = '4538d3e7-ca68-4226-9b71-54f092590a5c';

    public function testItShouldBeCreatedWithIdEmptyStrategiesAndStatus(): void
    {
        $feature = new Feature(
            self::FEATURE_ID,
            new ToggleStrategies(),
            false
        );

        $this->assertSame(self::FEATURE_ID, $feature->id());
        $this->assertCount(0, $feature->strategies());
        $this->assertFalse($feature->isEnabled());
    }

    public function testItShouldBeCreatedWithIdStrategiesAndStatus(): void
    {
        $feature = new Feature(
            self::FEATURE_ID,
            new ToggleStrategies(
                $this->createMock(ToggleStrategy::class),
                $this->createMock(ToggleStrategy::class),
                $this->createMock(ToggleStrategy::class),
            ),
            true
        );

        $this->assertSame(self::FEATURE_ID, $feature->id());
        $this->assertCount(3, $feature->strategies());
        $this->assertTrue($feature->isEnabled());
    }
}
