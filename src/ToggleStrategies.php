<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle;

final class ToggleStrategies
{
    /** @var ToggleStrategy[] */
    private array $strategies;

    public function __construct(ToggleStrategy ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @return ToggleStrategy[]
     */
    public function get(): array
    {
        return $this->strategies;
    }
}
