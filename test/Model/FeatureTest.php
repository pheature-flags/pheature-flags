<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Model;

use Pheature\Crud\Toggle\Model\Feature;
use Pheature\Crud\Toggle\Model\FeatureId;
use PHPUnit\Framework\TestCase;

final class FeatureTest extends TestCase
{
    private const ID = '8d2fc079-8d5f-4140-8933-2472a2a17e97';

    public function testItShouldBeCreatedWithId(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::ID));
        $this->assertSame($feature->id(), self::ID);
        $this->assertFalse($feature->isEnabled());
    }
}
