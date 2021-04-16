<?php

declare(strict_types=1);

namespace Pheature\InMemory\Toggle;

use Exception;
use Traversable;

final class InMemoryConfig
{
    private array $config;

    public function __construct(array $config = [])
    {
        $this->assertConfig($config);
        $this->config = $config;
    }

    public function get(string $featureId): array
    {
        return $this->config[$featureId];
    }

    public function has(string $featureId): bool
    {
        return array_key_exists($featureId, $this->config);
    }

    private function assertConfig(array $config): void
    {
    }

    public function all(): array
    {
        return $this->config;
    }
}
