<?php

declare(strict_types=1);

namespace Pheature\InMemory\Toggle;

use Exception;
use Traversable;

final class InMemoryConfig
{
    /** @var array<string, mixed> */
    private array $config;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->assertConfig($config);
        $this->config = $config;
    }

    /**
     * @param string $featureId
     * @return array<string, string|bool|array<string, mixed>>
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function get(string $featureId): array
    {
        return $this->config[$featureId];
    }

    public function has(string $featureId): bool
    {
        return array_key_exists($featureId, $this->config);
    }

    /**
     * @psalm-suppress UnusedParam
     * @param array<string, mixed> $config
     */
    private function assertConfig(array $config): void
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->config;
    }
}
