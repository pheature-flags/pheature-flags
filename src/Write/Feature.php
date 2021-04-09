<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use JsonSerializable;

use function array_map;

final class Feature implements JsonSerializable
{
    private FeatureId $featureId;
    private bool $enabled;
    /** @var Strategy[] */
    private array $strategies = [];

    /**
     * Feature constructor.
     * @param FeatureId $featureId
     * @param bool $enabled
     * @param Strategy[] $strategies
     */
    public function __construct(FeatureId $featureId, bool $enabled, array $strategies = [])
    {
        $this->featureId = $featureId;
        $this->enabled = $enabled;
        $this->strategies = $strategies;
    }

    public static function withId(FeatureId $featureId): self
    {
        return new self($featureId, false);
    }

    public function addStrategy(Strategy $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function id(): string
    {
        return $this->featureId->value();
    }

    /**
     * @return Strategy[]
     */
    public function strategies(): array
    {
        return $this->strategies;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'feature_id' => $this->featureId->value(),
            'enabled' => $this->enabled,
            'strategies' => array_map(static fn(Strategy $strategy) => $strategy->jsonSerialize(), $this->strategies),
        ];
    }
}
