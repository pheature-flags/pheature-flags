<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle\Read;

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\Feature as IFeature;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Model\Toggle\Feature;

use function array_map;
use function json_decode;

final class DbalFeatureFactory
{
    private const MAX_DEPTH = 512;
    private ChainToggleStrategyFactory $strategyFactory;

    public function __construct(ChainToggleStrategyFactory $strategyFactory)
    {
        $this->strategyFactory = $strategyFactory;
    }

    /**
     * @param array<string, string|bool|array<string, mixed>> $data
     * @return IFeature
     */
    public function create(array $data): IFeature
    {
        /** @var string $id */
        $id = $data['feature_id'];
        $enabled = (bool)$data['enabled'];
        /** @var string $jsonStrategies */
        $jsonStrategies = $data['strategies'];
        /** @var array<string, array<string, mixed>> $strategies */
        $strategies = json_decode($jsonStrategies, true, self::MAX_DEPTH, JSON_THROW_ON_ERROR);

        return new Feature(
            $id,
            new ToggleStrategies(
                ...array_map(
                    fn(array $strategy) => $this->strategyFactory->createFromArray($strategy),
                    $strategies
                )
            ),
            $enabled
        );
    }
}
