<?php

declare(strict_types=1);

namespace Pheature\Sdk;

final class OnDisabledFeature implements OnFeatureState
{
    /** @var callable */
    private $callback;
    /** @var array<string, mixed>  */
    private array $arguments;

    private function __construct()
    {
    }

    /**
     * @param callable $callback
     * @param array<string, mixed> $arguments
     * @return static
     */
    public static function make(callable $callback, array $arguments = []): self
    {
        $self = new self();
        $self->callback = $callback;
        $self->arguments = $arguments;

        return $self;
    }

    public function callback(): callable
    {
        return $this->callback;
    }

    /**
     * @return array<string, mixed>
     */
    public function arguments(): array
    {
        return $this->arguments;
    }
}
