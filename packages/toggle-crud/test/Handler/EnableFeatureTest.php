<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Command\EnableFeature as EnableFeatureCommand;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use PHPUnit\Framework\TestCase;

final class EnableFeatureTest extends TestCase
{
    private const FEATURE_ID = '252f6942-20ac-4b69-960a-d4246b1895c8';

    public function testItShouldEnableAFeature(): void
    {
        $feature = Feature::withId(FeatureId::fromString(self::FEATURE_ID));
        $command = EnableFeatureCommand::withId(self::FEATURE_ID);
        $repository = $this->createMock(FeatureRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $repository->expects($this->once())
            ->method('save')
            ->with($feature);

        $handler = new EnableFeature($repository);
        $handler->handle($command);
        $this->assertTrue($feature->isEnabled());
    }
}
