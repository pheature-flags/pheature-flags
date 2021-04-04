<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

interface Segment
{
    public function id(): string;
    /** @return array<string, mixed> */
    public function criteria(): array;
    /**
     * @param array<string, mixed> $payload
     * @return bool
     */
    public function match(array $payload): bool;
}
