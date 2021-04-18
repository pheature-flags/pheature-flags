<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use InvalidArgumentException;
use Pheature\Crud\Toggle\Command\RemoveFeature as RemoveFeatureCommand;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webmozart\Assert\Assert;

final class DeleteFeature implements RequestHandlerInterface
{
    private RemoveFeature $removeFeature;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(RemoveFeature $removeFeature, ResponseFactoryInterface $responseFactory)
    {
        $this->removeFeature = $removeFeature;
        $this->responseFactory = $responseFactory;
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $featureId = $request->getAttribute('feature_id');

        try {
            Assert::string($featureId);
        } catch (InvalidArgumentException $exception) {
            return $this->responseFactory->createResponse(404, 'Route Not Found.');
        }

        $this->removeFeature->handle(
            RemoveFeatureCommand::withId($featureId)
        );

        return $this->responseFactory->createResponse(204, 'Deleted');
    }
}
