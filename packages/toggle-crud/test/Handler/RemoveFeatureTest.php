<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Command\RemoveFeature as RemoveFeatureCommand;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Pheature\Core\Toggle\Write\FeatureId;
use PHPUnit\Framework\TestCase;

final class RemoveFeatureTest extends TestCase
{
    private const FEATURE_ID = '252f6942-20ac-4b69-960a-d4246b1895c8';

    public function testItShouldRemoveAFeature(): void
    {
        $featureId = FeatureId::fromString(self::FEATURE_ID);
        $command = RemoveFeatureCommand::withId(self::FEATURE_ID);
        $repository = $this->createMock(FeatureRepository::class);
        $repository->expects($this->once())
            ->method('remove')
            ->with($featureId);

        $handler = new RemoveFeature($repository);
        $handler->handle($command);
    }
}
