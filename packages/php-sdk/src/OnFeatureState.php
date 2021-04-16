<?php

declare(strict_types=1);

namespace Pheature\Sdk;

interface OnFeatureState
{
    public function callback(): callable;
    public function arguments(): array;
}
