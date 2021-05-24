<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Core\Toggle\Write\Strategy;
use Pheature\Crud\Toggle\Command\SetStrategy as AddStrategyCommand;

final class AddStrategy
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function handle(AddStrategyCommand $command): void
    {
        $feature = $this->featureRepository->get($command->featureId());

        $feature->addStrategy(
            new Strategy($command->strategyId(), $command->strategyType(), $command->segments())
        );

        $this->featureRepository->save($feature);
    }
}
