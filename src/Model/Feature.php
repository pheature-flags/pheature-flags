<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Model;

use Pheature\Core\Toggle\Write\Feature as IFeature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\Strategy;

final class Feature implements IFeature
{
    private FeatureId $featureId;
    private bool $enabled;
    /** @var Strategy[] */
    private array $strategies = [];

    public function __construct(FeatureId $featureId, bool $enabled)
    {
        $this->featureId = $featureId;
        $this->enabled = $enabled;
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

    public function strategies(): array
    {
        return $this->strategies;
    }
}
