<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use Generator;
use IteratorAggregate;

use function count;

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

    public function count(): int
    {
        return count($this->strategies);
    }

    /**
     * @return Generator<ToggleStrategy>
     */
    public function getIterator(): Generator
    {
        yield from $this->strategies;
    }
}
