<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

interface Feature
{
    public function id(): string;
    public function strategies(): ToggleStrategies;
    public function isEnabled(): bool;
}
