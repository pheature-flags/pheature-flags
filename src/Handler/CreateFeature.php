<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Handler;

use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Command\CreateFeature as CreateFeatureCommand;
use Pheature\Crud\Toggle\Model\Feature;

final class CreateFeature
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function handle(CreateFeatureCommand $command): void
    {
        $feature = Feature::withId($command->featureId());
        $this->featureRepository->save($feature);
    }
}
