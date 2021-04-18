<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use Pheature\Crud\Toggle\Command\CreateFeature as CreateFeatureCommand;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        $this->createFeature->handle(
            CreateFeatureCommand::disabled($request->getAttribute('feature_id'))
        );

        return $this->responseFactory->createResponse(201, 'Created');
    }
}
