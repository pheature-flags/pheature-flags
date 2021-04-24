<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use function array_key_exists;
use function array_values;

final class Segments
{
    /**
     * @var Segment[]
     */
    private array $segments = [];

    public function __construct(Segment ...$segments)
    {
        $this->segments = $segments;
    }

    /**
     * @return Segment[]
     */
    public function all(): array
    {
        return $this->segments;
    }
}
