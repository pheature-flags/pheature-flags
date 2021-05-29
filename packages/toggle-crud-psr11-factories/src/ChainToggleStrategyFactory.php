<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr11\Toggle;

use Pheature\Core\Toggle\Read\ToggleStrategyFactory;
use Pheature\Model\Toggle\SegmentFactory as ModelSegmentFactory;
use Psr\Container\ContainerInterface;
use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory as ReadToggleStrategyFactory;

class ChainToggleStrategyFactory
{
    public function __invoke(ContainerInterface $container): ReadToggleStrategyFactory
    {
        /** @var ModelSegmentFactory $segmentFactory */
        $segmentFactory = $container->get(ModelSegmentFactory::class);
        /** @var ToggleConfig $toggleConfig */
        $toggleConfig = $container->get(ToggleConfig::class);

        return self::create(
            $segmentFactory,
            ...array_map(
                function (array $strategyType) use ($container) {
                    /** @var ToggleStrategyFactory $toggleStrategyFactory */
                    $toggleStrategyFactory = $container->get($strategyType['type']);

                    return $toggleStrategyFactory;
                },
                $toggleConfig->strategyTypes()
            )
        );
    }

    public static function create(
        ModelSegmentFactory $segmentFactory,
        ToggleStrategyFactory ...$toggleStrategyFactories
    ): ReadToggleStrategyFactory {
        return new ReadToggleStrategyFactory($segmentFactory, ...$toggleStrategyFactories);
    }
}
