<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Webmozart\Assert\Assert;

final class ToggleConfig
{
    private string $driver;
    private string $apiPrefix;
    /** @var array<string, mixed> */
    private array $toggles = [];

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config)
    {
        Assert::keyExists($config, 'api_prefix');
        Assert::keyExists($config, 'driver');
        Assert::string($config['api_prefix']);
        Assert::string($config['driver']);
        $this->apiPrefix = $config['api_prefix'];
        $this->driver = $config['driver'];
        if (array_key_exists('toggles', $config)) {
            Assert::isArray($config['toggles']);
            /** @var array<string, mixed> $toggles */
            $toggles = $config['toggles'];
            $this->toggles = $toggles;
        }
    }

    public function apiPrefix(): string
    {
        return $this->apiPrefix;
    }

    public function driver(): string
    {
        return $this->driver;
    }

    /**
     * @return array<string, mixed>
     */
    public function toggles(): array
    {
        return $this->toggles;
    }
}
