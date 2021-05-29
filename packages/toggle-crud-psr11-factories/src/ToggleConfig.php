<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Webmozart\Assert\Assert;

final class ToggleConfig
{
    public const DRIVER_IN_MEMORY = 'inmemory';
    public const DRIVER_DBAL = 'dbal';
    private const VALID_DRIVERS = [
        self::DRIVER_IN_MEMORY,
        self::DRIVER_DBAL,
    ];

    private string $driver;
    private bool $apiEnabled;
    private string $apiPrefix;
    /** @var array<array<string, string>> */
    private array $strategyTypes;
    /** @var array<string, mixed> */
    private array $toggles;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config)
    {
        $this->assertDriver($config);
        Assert::keyExists($config, 'api_enabled');
        Assert::boolean($config['api_enabled']);
        Assert::keyExists($config, 'api_prefix');
        Assert::string($config['api_prefix']);

        $this->apiEnabled = $config['api_enabled'];
        $this->apiPrefix = $config['api_prefix'];
        $this->driver = (string) $config['driver'];
        $this->strategyTypes = [];
        $this->toggles = [];

        if (array_key_exists('strategy_types', $config)) {
            Assert::isArray($config['strategy_types']);
            /** @var array<array<string, string>> $strategyTypes */
            $strategyTypes = $config['strategy_types'];
            $this->strategyTypes = $strategyTypes;
        }

        if (array_key_exists('toggles', $config)) {
            Assert::isArray($config['toggles']);
            /** @var array<string, mixed> $toggles */
            $toggles = $config['toggles'];
            $this->toggles = $toggles;
        }
    }

    /**
     * @param array<string, mixed> $config
     * @return void
     */
    private function assertDriver(array $config): void
    {
        Assert::keyExists($config, 'driver');
        Assert::string($config['driver']);
        Assert::inArray($config['driver'], self::VALID_DRIVERS);
    }

    public function apiEnabled(): bool
    {
        return $this->apiEnabled;
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
     * @return array<array<string, string>>
     */
    public function strategyTypes(): array
    {
        return $this->strategyTypes;
    }

    /**
     * @return array<string, mixed>
     */
    public function toggles(): array
    {
        return $this->toggles;
    }
}
