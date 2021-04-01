<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Crud\Toggle\Command\EnableFeature as EnableFeatureCommand;
use Pheature\Crud\Toggle\FeatureRepository;

class EnableFeature
{
    private FeatureRepository $repository;

    public function __construct(FeatureRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(EnableFeatureCommand $command): void
    {
        $feature = $this->repository->get($command->featureId());

        $feature->enable();

        $this->repository->save($feature);
    }
}
