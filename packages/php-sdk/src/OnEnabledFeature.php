<?php

declare(strict_types=1);

namespace Pheature\Sdk;

final class OnEnabledFeature implements OnFeatureState
{
    /**
     * @var callable
     */
    private $callback;
    private array $arguments;

    private function __construct()
    {
    }

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

    public function arguments(): array
    {
        return $this->arguments;
    }
}
