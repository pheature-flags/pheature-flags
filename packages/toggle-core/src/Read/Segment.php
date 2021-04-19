<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

use JsonSerializable;

interface Segment extends JsonSerializable
{
    public function id(): string;
    /**
     * @return array<string, mixed>
     */
    public function criteria(): array;
    /**
     * @param  array<string, mixed> $payload
     * @return bool
     */
    public function match(array $payload): bool;
    /**
     * @return array<string, string|array>
     */
    public function toArray(): array;
}
