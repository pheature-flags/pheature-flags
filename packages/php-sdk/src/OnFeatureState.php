<?php

declare(strict_types=1);

namespace Pheature\Sdk;

interface OnFeatureState
{
    public function callback(): callable;

    /**
     * @return array<string, mixed>
     */
    public function arguments(): array;
}
