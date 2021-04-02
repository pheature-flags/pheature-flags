<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle;

use Pheature\Core\Toggle\Read\Feature as IFeature;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Model\Toggle\Feature;

final class DbalFeatureFactory
{
    public function create(array $data): IFeature
    {
        $strategies = json_decode($data['strategies'], true, 12, JSON_THROW_ON_ERROR);

        return new Feature(
            $data['feature_id'],
            new ToggleStrategies(...$strategies),
            (bool)(int)$data['enabled']
        );
    }
}
