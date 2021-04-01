<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Feature as IFeature;
use Pheature\Core\Toggle\ToggleStrategies;

final class Feature implements IFeature
{
    private string $id;
    private ToggleStrategies $strategies;
    private bool $enabled;

    public function __construct(
        string $id,
        ToggleStrategies $strategies,
        bool $enabled
    ) {
        $this->id = $id;
        $this->strategies = $strategies;
        $this->enabled = $enabled;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function strategies(): ToggleStrategies
    {
        return $this->strategies;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
