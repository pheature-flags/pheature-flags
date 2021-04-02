<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

interface ToggleStrategy
{
    public function segments(): Segments;
    public function isSatisfiedBy(ConsumerIdentity $identity): bool;
}
