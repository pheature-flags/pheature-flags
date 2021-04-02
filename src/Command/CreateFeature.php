<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Command;

use Pheature\Core\Toggle\Write\FeatureId;

final class CreateFeature
{
    private FeatureId $featureId;

    private function __construct(string $featureId)
    {
        $this->featureId = FeatureId::fromString($featureId);
    }

    public static function disabled(string $featureId): self
    {
        return new self($featureId);
    }

    public function featureId(): FeatureId
    {
        return $this->featureId;
    }
}
