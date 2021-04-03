<?php

declare(strict_types=1);

namespace Pheature\InMemory\Toggle;

use Pheature\Core\Toggle\Read\Feature as IFeature;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Model\Toggle\Feature;

final class InMemoryFeatureFactory
{
    public function create(array $data): IFeature
    {
        return new Feature(
            $data['id'],
            new ToggleStrategies(...$data['strategies']),
            $data['enabled']
        );
    }
}
