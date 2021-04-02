<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Command\DisableFeature as DisableFeatureCommand;

final class DisableFeature
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $repository)
    {
        $this->featureRepository = $repository;
    }

    public function handle(DisableFeatureCommand $command): void
    {
        $feature = $this->featureRepository->get($command->featureId());

        $feature->disable();

        $this->featureRepository->save($feature);
    }
}
