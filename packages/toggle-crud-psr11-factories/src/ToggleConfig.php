<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

final class ToggleConfig
{
    /** @var array<string, mixed>  */
    private array $config;

    /**
     * @param array<string, mixed> $config
     */
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
