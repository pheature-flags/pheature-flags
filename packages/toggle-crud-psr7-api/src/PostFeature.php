<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use InvalidArgumentException;
use Pheature\Crud\Toggle\Command\CreateFeature as CreateFeatureCommand;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webmozart\Assert\Assert;

final class PostFeature implements RequestHandlerInterface
{
    private CreateFeature $createFeature;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(CreateFeature $createFeature, ResponseFactoryInterface $responseFactory)
    {
        $this->createFeature = $createFeature;
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

        $this->createFeature->handle(
            CreateFeatureCommand::disabled($featureId)
        );

        return $this->responseFactory->createResponse(201, 'Created.');
    }
}
