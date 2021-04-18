<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetFeatures implements RequestHandlerInterface
{
    private FeatureFinder $featureFinder;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(FeatureFinder $featureFinder, ResponseFactoryInterface $responseFactory)
    {
        $this->featureFinder = $featureFinder;
        $this->responseFactory = $responseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $features = $this->featureFinder->all();

        $response = $this->responseFactory->createResponse();
        $response = $response->withAddedHeader('Content-Type', 'application/json');
        $response->getBody()->write(
            json_encode($features, JSON_THROW_ON_ERROR)
        );

        return $response;
    }
}
