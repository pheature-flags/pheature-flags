<?php

declare(strict_types=1);

namespace Pheature\InMemory\Toggle;

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\Feature as IFeature;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Model\Toggle\Feature;

final class InMemoryFeatureFactory
{
    private ChainToggleStrategyFactory $toggleStrategyFactory;

    public function __construct(ChainToggleStrategyFactory $toggleStrategyFactory)
    {
        $this->toggleStrategyFactory = $toggleStrategyFactory;
    }

    /**
     * @param array<string, string|bool|array<string, mixed>> $data
     * @return IFeature
     */
    public function create(array $data): IFeature
    {
        /** @var string $id */
        $id = $data['id'];
        $enabled = (bool)$data['enabled'];
        /** @var array<string, array<string, mixed>> $strategies */
        $strategies = $data['strategies'] ?? [];
        return new Feature(
            $id,
            /** @param array<string, array<string, mixed>> $strategies */
            new ToggleStrategies(...array_map(
                fn(array $strategy) => $this->toggleStrategyFactory->createFromArray($strategy),
                $strategies
            )),
            $enabled
        );
    }
}
