<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use Generator;
use IteratorAggregate;
use JsonSerializable;

use function count;

/**
 * @implements IteratorAggregate<ToggleStrategy>
 */
final class ToggleStrategies implements IteratorAggregate, JsonSerializable
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

    /**
     * @return array<array<mixed>>
     */
    public function jsonSerialize(): array
    {
        return array_map(
            static fn (ToggleStrategy $strategy): array => $strategy->toArray(),
            $this->strategies
        );
    }
}
