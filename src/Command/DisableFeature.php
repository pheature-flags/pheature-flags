<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Command;

use Pheature\Crud\Toggle\Model\FeatureId;

final class DisableFeature
{
    private FeatureId $featureId;

    private function __construct(FeatureId $featureId)
    {
        $this->featureId = $featureId;
    }

    public static function withId(string $featureId): self
    {
        return new self(FeatureId::fromString($featureId));
    }

    public function featureId(): FeatureId
    {
        return $this->featureId;
    }
}
