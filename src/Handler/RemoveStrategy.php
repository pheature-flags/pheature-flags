<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Command\RemoveStrategy as RemoveStrategyCommand;

final class RemoveStrategy
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function handle(RemoveStrategyCommand $command): void
    {
        $feature = $this->featureRepository->get($command->featureId());

        $feature->removeStrategy($command->strategyId());

        $this->featureRepository->save($feature);
    }
}
