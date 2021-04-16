<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

final class SegmentId
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $strategyId): self
    {
        return new self($strategyId);
    }

    public function value(): string
    {
        return $this->value;
    }
}
