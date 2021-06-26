<?php

declare(strict_types=1);

namespace Pheature\InMemory\Toggle;

use Webmozart\Assert\Assert;

final class InMemoryConfig
{
    /** @var array<string, mixed> */
    private array $config = [];

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->assertConfig($config);
        /** @var array<string, mixed> $feature */
        foreach ($config as $feature) {
            $this->config[(string)$feature['id']] = $feature;
        }
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
     * @param array<string, mixed> $config
     */
    private function assertConfig(array $config): void
    {
        foreach ($config as $toggleConfig) {
            Assert::isArray($toggleConfig);
            Assert::keyExists($toggleConfig, 'id');
            Assert::string($toggleConfig['id']);
            Assert::keyExists($toggleConfig, 'enabled');
            Assert::boolean($toggleConfig['enabled']);
            Assert::nullOrIsArray($toggleConfig['strategies']);
            /** @var array<string, mixed> $strategy */
            foreach ($toggleConfig['strategies'] ?? [] as $strategy) {
                Assert::keyExists($strategy, 'strategy_id');
                Assert::string($strategy['strategy_id']);
                Assert::keyExists($strategy, 'strategy_type');
                Assert::string($strategy['strategy_type']);
                Assert::nullOrIsArray($strategy['segments']);
                /** @var array<string, mixed> $segment */
                foreach ($strategy['segments'] ?? [] as $segment) {
                    Assert::keyExists($segment, 'segment_id');
                    Assert::string($segment['segment_id']);
                    Assert::keyExists($segment, 'segment_type');
                    Assert::string($segment['segment_type']);
                }
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->config;
    }
}
