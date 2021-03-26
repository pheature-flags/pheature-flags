<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle;

use Generator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<ToggleStrategy>
 */
final class ToggleStrategies implements IteratorAggregate
{
    /** @var ToggleStrategy[] */
    private array $strategies;

    public function __construct(ToggleStrategy ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @return Generator<ToggleStrategy>
     */
    public function getIterator(): Generator
    {
        yield from $this->strategies;
    }
}
