<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Core\Toggle\Write\StrategyFactory;
use Pheature\Crud\Toggle\Command\AddStrategy as AddStrategyCommand;

final class AddStrategy
{
    private FeatureRepository $featureRepository;
    private StrategyFactory $strategyFactory;

    public function __construct(FeatureRepository $featureRepository, StrategyFactory $strategyFactory)
    {
        $this->featureRepository = $featureRepository;
        $this->strategyFactory = $strategyFactory;
    }

    public function handle(AddStrategyCommand $command): void
    {
        $feature = $this->featureRepository->get($command->featureId());

        $feature->addStrategy(
            $this->strategyFactory->makeFromType($command->strategyId(), $command->strategyType())
        );

        $this->featureRepository->save($feature);
    }
}
