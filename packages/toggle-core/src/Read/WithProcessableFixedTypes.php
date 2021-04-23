<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

interface WithProcessableFixedTypes
{
    /**
     * @return array<string>
     */
    public function types(): array;
}
