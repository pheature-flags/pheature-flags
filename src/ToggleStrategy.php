<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle;

interface ToggleStrategy
{
    public function segments(): Segments;
    public function isSatisfiedBy(ConsumerIdentity $identity): bool;
}
