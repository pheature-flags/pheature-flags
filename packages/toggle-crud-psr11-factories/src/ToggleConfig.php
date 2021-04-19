<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

final class ToggleConfig
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function prefix(): string
    {
        return $this->config['prefix'];
    }

    public function driver(): string
    {
        return $this->config['driver'];
    }
}
