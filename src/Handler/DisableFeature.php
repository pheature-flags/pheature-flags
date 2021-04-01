<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Crud\Toggle\Command\DisableFeature as DisableFeatureCommand;
use Pheature\Crud\Toggle\FeatureRepository;

class DisableFeature
{
    private FeatureRepository $repository;

    public function __construct(FeatureRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DisableFeatureCommand $command): void
    {
        $feature = $this->repository->get($command->featureId());

        $feature->disable();

        $this->repository->save($feature);
    }
}
