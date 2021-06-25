<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle\Container;

use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\SegmentFactoryFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrategyFactoryFactory;

final class ConfigProvider
{
    /**
     * @return array<string, array<string, array<string, string>>>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    StrategyFactory::class => StrategyFactoryFactory::class,
                    SegmentFactory::class => SegmentFactoryFactory::class,
                ],
            ],
        ];
    }
}
