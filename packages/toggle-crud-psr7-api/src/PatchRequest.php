<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use Pheature\Crud\Toggle\Command\SetStrategy;
use Pheature\Crud\Toggle\Command\DisableFeature;
use Pheature\Crud\Toggle\Command\EnableFeature;
use Pheature\Crud\Toggle\Command\RemoveStrategy;
use Psr\Http\Message\ServerRequestInterface;
use Webmozart\Assert\Assert;

final class PatchRequest
{
    private const ACTION_ENABLE_FEATURE = 'enable_feature';
    private const ACTION_DISABLE_FEATURE = 'disable_feature';
    private const ACTION_SET_STRATEGY = 'set_strategy';
    private const ACTION_REMOVE_STRATEGY = 'remove_strategy';

    private string $featureId;
    private string $action;
    /**
     * @var array<string|mixed>|null
     */
    private ?array $requestData = null;

    public function __construct(string $featureId, ServerRequestInterface $request)
    {
        $body = (array)$request->getParsedBody();
        $action = $body['action'] ?? null;
        Assert::string($action);

        $value = $body['value'] ?? null;
        Assert::nullOrIsArray($value);

        $this->featureId = $featureId;
        $this->action = $action;
        $this->requestData = $value;
    }

    public function setStrategyCommand(): SetStrategy
    {
        Assert::notNull($this->requestData);
        Assert::keyExists($this->requestData, 'strategy_id');
        Assert::keyExists($this->requestData, 'strategy_type');
        Assert::string($this->requestData['strategy_id']);
        Assert::string($this->requestData['strategy_type']);
        /** @var array<array<string, mixed>> $segments */
        $segments = $this->requestData['segments'] ?? [];
        if (false === empty($segments)) {
            foreach ($segments as $segment) {
                Assert::keyExists($segment, 'segment_id');
                Assert::keyExists($segment, 'segment_type');
                Assert::keyExists($segment, 'criteria');
                Assert::string($segment['segment_id']);
                Assert::string($segment['segment_type']);
                Assert::isArray($segment['criteria']);
            }
        }

        return SetStrategy::withIdTypeAndSegments(
            $this->featureId,
            $this->requestData['strategy_id'],
            $this->requestData['strategy_type'],
            $segments
        );
    }

    public function removeStrategyCommand(): RemoveStrategy
    {
        Assert::notNull($this->requestData);
        Assert::keyExists($this->requestData, 'strategy_id');
        Assert::string($this->requestData['strategy_id']);

        return RemoveStrategy::withFeatureAndStrategyId(
            $this->featureId,
            $this->requestData['strategy_id']
        );
    }

    public function enableFeatureCommand(): EnableFeature
    {
        return EnableFeature::withId($this->featureId);
    }

    public function disableFeatureCommand(): DisableFeature
    {
        return DisableFeature::withId($this->featureId);
    }

    public function isSetStrategyAction(): bool
    {
        return self::ACTION_SET_STRATEGY === $this->action;
    }

    public function isRemoveStrategyAction(): bool
    {
        return self::ACTION_REMOVE_STRATEGY === $this->action;
    }

    public function isEnableFeatureAction(): bool
    {
        return self::ACTION_ENABLE_FEATURE === $this->action;
    }

    public function isDisableFeatureAction(): bool
    {
        return self::ACTION_DISABLE_FEATURE === $this->action;
    }
}
