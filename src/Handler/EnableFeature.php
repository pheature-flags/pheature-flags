<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Command\EnableFeature as EnableFeatureCommand;

class EnableFeature
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $repository)
    {
        $this->featureRepository = $repository;
    }

    public function handle(EnableFeatureCommand $command): void
    {
        $feature = $this->featureRepository->get($command->featureId());

        $feature->enable();

        $this->featureRepository->save($feature);
    }
}
