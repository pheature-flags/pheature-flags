<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use Pheature\Crud\Toggle\Command\AddStrategy;
use Pheature\Crud\Toggle\Command\DisableFeature;
use Pheature\Crud\Toggle\Command\EnableFeature;
use Pheature\Crud\Toggle\Command\RemoveStrategy;
use Psr\Http\Message\ServerRequestInterface;
use Webmozart\Assert\Assert;

final class PatchRequest
{
    private const ACTION_ENABLE_FEATURE = 'enable_feature';
    private const ACTION_DISABLE_FEATURE = 'disable_feature';
    private const ACTION_ADD_STRATEGY = 'add_strategy';
    private const ACTION_REMOVE_STRATEGY = 'remove_strategy';

    private string $featureId;
    private string $action;
    /** @var array<string|mixed>|null  */
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

    public function addStrategyCommand(): AddStrategy
    {
        Assert::notNull($this->requestData);
        Assert::keyExists($this->requestData, 'strategy_id');
        Assert::keyExists($this->requestData, 'strategy_type');
        Assert::string($this->requestData['strategy_id']);
        Assert::string($this->requestData['strategy_type']);

        return AddStrategy::withIdAndType(
            $this->featureId,
            $this->requestData['strategy_id'],
            $this->requestData['strategy_type']
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

    public function isAddStrategyAction(): bool
    {
        return self::ACTION_ADD_STRATEGY === $this->action;
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
