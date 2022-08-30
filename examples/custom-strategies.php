<?php

declare(strict_types=1);

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\ConsumerIdentity;
use Pheature\Core\Toggle\Read\Segments;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Read\ToggleStrategy;
use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;

require '../vendor/autoload.php';

final class SuperCoolRollupStrategy implements ToggleStrategy
{
    public const NAME = 'super_cool_rollout_strategy';

    public function __construct(
        private readonly string $id
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

    public function isSatisfiedBy(ConsumerIdentity $identity): bool
    {
        // TODO: Super cool business logic
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

final class SuperCoolRollupStrategyFactory implements ToggleStrategyFactory
{
    public function create(string $strategyId, string $strategyType, ?Segments $segments = null): ToggleStrategy
    {
        return new SuperCoolRollupStrategy($strategyId);
    }

    public function types(): array
    {
        return [SuperCoolRollupStrategy::NAME];
    }
}

$config = include 'config.php';

$toggle = new Toggle(
    new InMemoryFeatureFinder(
        new InMemoryConfig($config['toggles']),
        new InMemoryFeatureFactory(
            new ChainToggleStrategyFactory(
                new \Pheature\Model\Toggle\SegmentFactory(),
                new SuperCoolRollupStrategyFactory(),
                new \Pheature\Model\Toggle\StrategyFactory()
            )
        )
    )
);

if ($toggle->isEnabled('super_cool_rollout_strategy')) {
    echo 'Super Cool Feature^^.' . PHP_EOL;
}
