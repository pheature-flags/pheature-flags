<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

final class SegmentType
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $strategyType): self
    {
        return new self($strategyType);
    }

    public function value(): string
    {
        return $this->value;
    }
}
