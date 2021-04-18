<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use Pheature\Crud\Toggle\Command\AddStrategy as AddStrategyCommand;
use Pheature\Crud\Toggle\Command\DisableFeature as DisableFeatureCommand;
use Pheature\Crud\Toggle\Command\EnableFeature as EnableFeatureCommand;
use Pheature\Crud\Toggle\Command\RemoveStrategy as RemoveStrategyCommand;
use Pheature\Crud\Toggle\Handler\AddStrategy;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PatchFeature implements RequestHandlerInterface
{
    private AddStrategy $addStrategy;
    private RemoveStrategy $removeStrategy;
    private EnableFeature $enableFeature;
    private DisableFeature $disableFeature;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(
        AddStrategy $addStrategy,
        RemoveStrategy $removeStrategy,
        EnableFeature $enableFeature,
        DisableFeature $disableFeature,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->addStrategy = $addStrategy;
        $this->removeStrategy = $removeStrategy;
        $this->enableFeature = $enableFeature;
        $this->disableFeature = $disableFeature;
        $this->responseFactory = $responseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $featureId = $request->getAttribute('feature_id');
        $body = $request->getParsedBody();

        $action = $body['action'];
        $value = $body['value'] ?? null;

        if ('add_strategy' === $action) {
            $this->addStrategy($action, $featureId, $value);
        }
        if ('remove_strategy' === $action) {
            $this->removeStrategy($action, $featureId, $value);
        }
        if ('enable_feature' === $action) {
            $this->enableFeature($action, $featureId);
        }
        if ('disable_feature' === $action) {
            $this->disableFeature($action, $featureId);
        }

        return $this->responseFactory->createResponse(202, 'Processed');
    }

    private function addStrategy(string $action, string $featureId, array $strategy): void
    {
        $this->addStrategy->handle(
            AddStrategyCommand::withIdAndType(
                $featureId,
                $strategy['id'],
                $strategy['type']
            )
        );
    }

    private function removeStrategy(string $action, string $featureId, string $strategyId): void
    {
        $this->removeStrategy->handle(
            RemoveStrategyCommand::withFeatureAndStrategyId(
                $featureId,
                $strategyId
            )
        );
    }

    private function enableFeature(string $action, string $featureId): void
    {
        $this->enableFeature->handle(
            EnableFeatureCommand::withId($featureId)
        );
    }

    private function disableFeature(string $action, string $featureId): void
    {
        $this->disableFeature->handle(
            DisableFeatureCommand::withId($featureId)
        );
    }
}
