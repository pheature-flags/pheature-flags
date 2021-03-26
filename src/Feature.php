<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle;

interface Feature
{
    public function id(): string;
    public function strategies(): ToggleStrategies;
    public function isEnabled(): bool;
}
