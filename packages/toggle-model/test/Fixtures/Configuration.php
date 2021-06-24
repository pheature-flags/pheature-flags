<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle\Fixtures;

use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;

final class Configuration
{
    public static function create(): array
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
