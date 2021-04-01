<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Model;

final class Feature
{
    private FeatureId $featureId;
    private bool $enabled;

    public function __construct(FeatureId $featureId, bool $enabled)
    {
        $this->featureId = $featureId;
        $this->enabled = $enabled;
    }

    public static function withId(FeatureId $featureId): self
    {
        return new self($featureId, false);
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
}
