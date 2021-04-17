<?php

declare(strict_types=1);

namespace Pheature\Crud\Toggle\Command;

use Pheature\Core\Toggle\Write\FeatureId;

final class EnableFeature
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
