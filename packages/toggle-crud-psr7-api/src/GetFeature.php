<?php

declare(strict_types=1);

namespace Pheature\Crud\Psr7\Toggle;

use InvalidArgumentException;
use Pheature\Core\Toggle\Exception\FeatureNotFoundException;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webmozart\Assert\Assert;

final class GetFeature implements RequestHandlerInterface
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
        $featureId = $request->getAttribute('feature_id');

        try {
            Assert::string($featureId);
            $feature = $this->featureFinder->get($featureId);
        } catch (FeatureNotFoundException | InvalidArgumentException $exception) {
            return $this->responseFactory->createResponse(404, 'Route Not Found.');
        }

        $response = $this->responseFactory->createResponse();
        $response = $response->withAddedHeader('Content-Type', 'application/json');
        $response->getBody()->write(
            json_encode($feature, JSON_THROW_ON_ERROR)
        );

        return $response;
    }
}
