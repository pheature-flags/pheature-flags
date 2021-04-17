<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Command\RemoveFeature as RemoveFeatureCommand;

final class RemoveFeature
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $repository)
    {
        $this->featureRepository = $repository;
    }

    public function handle(RemoveFeatureCommand $command): void
    {
        $this->featureRepository->remove($command->featureId());
    }
}
