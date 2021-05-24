<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use Pheature\Crud\Toggle\Handler\SetStrategy;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PatchFeature implements RequestHandlerInterface
{
    private SetStrategy $setStrategy;
    private RemoveStrategy $removeStrategy;
    private EnableFeature $enableFeature;
    private DisableFeature $disableFeature;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(
        SetStrategy $setStrategy,
        RemoveStrategy $removeStrategy,
        EnableFeature $enableFeature,
        DisableFeature $disableFeature,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->setStrategy = $setStrategy;
        $this->removeStrategy = $removeStrategy;
        $this->enableFeature = $enableFeature;
        $this->disableFeature = $disableFeature;
        $this->responseFactory = $responseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $featureId = $request->getAttribute('feature_id');
        if (false === is_string($featureId)) {
            return $this->responseFactory->createResponse(404, 'Route Not Found.');
        }

        try {
            $this->handleRequest($featureId, $request);
        } catch (\InvalidArgumentException $exception) {
            return $this->responseFactory->createResponse(400, 'Bad request.');
        }

        return $this->responseFactory->createResponse(202, 'Processed.');
    }

    private function handleRequest(string $featureId, ServerRequestInterface $request): void
    {
        $patchRequest = new PatchRequest($featureId, $request);
        if ($patchRequest->isEnableFeatureAction()) {
            $this->enableFeature->handle($patchRequest->enableFeatureCommand());
        }
        if ($patchRequest->isDisableFeatureAction()) {
            $this->disableFeature->handle($patchRequest->disableFeatureCommand());
        }
        if ($patchRequest->isSetStrategyAction()) {
            $this->setStrategy->handle($patchRequest->setStrategyCommand());
        }
        if ($patchRequest->isRemoveStrategyAction()) {
            $this->removeStrategy->handle($patchRequest->removeStrategyCommand());
        }
    }
}
