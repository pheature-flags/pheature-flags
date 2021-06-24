<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle\Container;

use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;

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
                    StrategyFactory::class => \Pheature\Crud\Psr11\Toggle\StrategyFactory::class,
                    SegmentFactory::class => \Pheature\Crud\Psr11\Toggle\SegmentFactory::class,
                ],
            ],
        ];
    }
}
