<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Read;

interface ConsumerIdentity
{
    public function id(): string;
    /**
     * @return array<string, mixed>
     */
    public function payload(): array;
}
