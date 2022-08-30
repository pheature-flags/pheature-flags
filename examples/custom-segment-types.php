<?php

declare(strict_types=1);

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\ConsumerIdentity;
use Pheature\Core\Toggle\Read\Segment;
use Pheature\Core\Toggle\Read\SegmentFactory;
use Pheature\Core\Toggle\Read\Segments;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Read\ToggleStrategy;
use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;

require '../vendor/autoload.php';

final class SuperCoolSegment implements Segment
{
    public const NAME = 'super_cool_segment';

    public function __construct(
        private readonly string $id,
        private readonly array $criteria,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function type(): string
    {
        return self::NAME;
    }

    public function criteria(): array
    {
        return $this->criteria;
    }

    public function match(array $payload): bool
    {
        // TODO: Super Cool Segment Logic
        return true;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => self::NAME,
            'segments' => [],
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}

final class SuperCoolSegmentFactory implements SegmentFactory
{
    public function create(string $segmentId, string $segmentType, array $criteria): Segment
    {
        return new SuperCoolSegment($segmentId, $criteria);
    }

    public function types(): array
    {
        return [SuperCoolSegment::NAME];
    }
}

$config = include 'config.php';

$toggle = new Toggle(
    new InMemoryFeatureFinder(
        new InMemoryConfig($config['toggles']),
        new InMemoryFeatureFactory(
            new ChainToggleStrategyFactory(
                new \Pheature\Core\Toggle\Read\ChainSegmentFactory(
                    new SuperCoolSegmentFactory(),
                    new \Pheature\Model\Toggle\SegmentFactory(),
                ),
                new \Pheature\Model\Toggle\StrategyFactory()
            )
        )
    )
);

if ($toggle->isEnabled('super_cool_segmented_rollout_strategy')) {
    echo 'Super Cool Feature by a Segment^^.' . PHP_EOL;
}
