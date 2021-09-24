<?php

declare(strict_types=1);

namespace Pheature\Community\Laravel;

use Illuminate\Support\Facades\Facade;
use Pheature\Core\Toggle\Read\ConsumerIdentity;
use Pheature\Sdk\CommandRunner;
use Pheature\Sdk\OnDisabledFeature;
use Pheature\Sdk\OnEnabledFeature;
use Pheature\Sdk\Result;

/**
 * Class Toggle
 *
 * @package Pheature\Community\Laravel
 * @codingStandardsIgnoreStart
 * @method static Result inFeature(string $featureId, ?ConsumerIdentity $identity = null, ?OnEnabledFeature $onEnabledFeature = null, ?OnDisabledFeature $onDisabledFeature = null)
 * @codingStandardsIgnoreEnd
 */
final class Toggle extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CommandRunner::class;
    }
}
