<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Toggle\Command;

use Pheature\Crud\Toggle\Command\CreateFeature as CreateFeatureCommand;
use Pheature\Crud\Toggle\FeatureRepository;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Pheature\Crud\Toggle\Model\Feature;
use PHPUnit\Framework\TestCase;

final class CreateFeatureTest extends TestCase
{
    private const FEATURE_ID = '3dc897eb-b1d8-407d-a7d7-08f4807a8155';

    public function testIsShouldCreateNewFeature(): void
    {
        $command = CreateFeatureCommand::disabled(self::FEATURE_ID);
        $repository = $this->createMock(FeatureRepository::class);
        $repository->expects(static::once())
            ->method('save')
            ->with($this->isInstanceOf(Feature::class));

        $handler = new CreateFeature($repository);

        $handler->handle($command);
    }
}