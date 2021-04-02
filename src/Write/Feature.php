<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

interface Feature
{
    public function enable(): void;

    public function disable(): void;

    public function isEnabled(): bool;

    public function id(): string;

    /**
     * @return Strategy[]
     */
    public function strategies(): array;
}
