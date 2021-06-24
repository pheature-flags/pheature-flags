<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Symfony\Fixtures;

use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;

final class PheatureFlagsConfig
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public static function createDefault(): self
    {
        $defaultConfig = [
            'driver' => 'inmemory',
            'api_prefix' => '',
            'api_enabled' => false,
            'segment_types' => [
                [
                    'type' => IdentitySegment::NAME,
                    'factory_id' => SegmentFactory::class
                ],
                [
                    'type' => StrictMatchingSegment::NAME,
                    'factory_id' => SegmentFactory::class
                ]
            ],
            'strategy_types' => [
                [
                    'type' => EnableByMatchingSegment::NAME,
                    'factory_id' => StrategyFactory::class
                ],
                [
                    'type' => EnableByMatchingIdentityId::NAME,
                    'factory_id' => StrategyFactory::class
                ]
            ],
            'toggles' => [],
        ];

        return new self($defaultConfig);
    }

    public function withApiEnabled(bool $enabled): self
    {
        $this->config['api_enabled'] = $enabled;

        return $this;
    }

    public function withApiPrefix(string $prefix): self
    {
        $this->config['api_prefix'] = $prefix;

        return $this;
    }

    public function build(): array
    {
        return $this->config;
    }
}
