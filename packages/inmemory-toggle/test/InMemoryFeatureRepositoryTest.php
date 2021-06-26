<?php

declare(strict_types=1);

namespace Pheature\Test\InMemory\Toggle;

use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\InMemory\Toggle\InMemoryFeatureNotFound;
use Pheature\InMemory\Toggle\InMemoryFeatureRepository;
use PHPUnit\Framework\TestCase;

final class InMemoryFeatureRepositoryTest extends TestCase
{
    public function testItShouldSaveFeatureAtInMemoryRepository(): void
    {
        $featureId = FeatureId::fromString('some_feature');
        $feature = new Feature(
            $featureId,
            false,
            []
        );

        $repository = new InMemoryFeatureRepository();
        $repository->save($feature);

        self::assertSame($feature, $repository->get($featureId));
    }

    public function testItShouldRemoveSavedFeatureAtInMemoryRepository(): void
    {
        $this->expectException(InMemoryFeatureNotFound::class);
        $featureId = FeatureId::fromString('some_feature');
        $feature = new Feature(
            $featureId,
            false,
            []
        );

        $repository = new InMemoryFeatureRepository();
        $repository->save($feature);
        self::assertSame($feature, $repository->get($featureId));
        $repository->remove($featureId);
        $repository->get($featureId);
    }
}
