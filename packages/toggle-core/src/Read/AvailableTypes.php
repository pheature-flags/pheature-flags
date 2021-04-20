<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

interface AvailableTypes
{
    /**
     * @return array<string>
     */
    public static function strategyTypes(): array;
}
