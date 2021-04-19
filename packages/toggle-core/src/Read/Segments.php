<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use function array_key_exists;
use function array_values;

final class Segments
{
    /**
     * @var array<string, Segment>
     */
    private array $segments = [];

    public function __construct(Segment ...$segments)
    {
        foreach ($segments as $segment) {
            $this->segments[$segment->id()] = $segment;
        }
    }

    /**
     * @return Segment[]
     */
    public function all(): array
    {
        return array_values($this->segments);
    }

    public function has(Segment $segment): bool
    {
        return array_key_exists($segment->id(), $this->segments);
    }
}
