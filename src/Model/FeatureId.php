<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Model;

final class FeatureId
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $featureId): self
    {
        return new self($featureId);
    }

    public function value(): string
    {
        return $this->value;
    }
}
