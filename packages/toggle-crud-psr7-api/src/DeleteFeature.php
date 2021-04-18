<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use Pheature\Crud\Toggle\Command\RemoveFeature as RemoveFeatureCommand;
use Pheature\Crud\Toggle\Handler\RemoveFeature;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        $this->removeFeature->handle(
            RemoveFeatureCommand::withId($request->getAttribute('feature_id'))
        );

        return $this->responseFactory->createResponse(204, 'Deleted');
    }
}
