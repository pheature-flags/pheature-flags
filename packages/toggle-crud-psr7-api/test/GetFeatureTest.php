<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr7\Toggle;

use Pheature\Core\Toggle\Exception\FeatureNotFoundException;
use Pheature\Core\Toggle\Read\Feature;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

final class GetFeatureTest extends TestCase
{
    public function testItShouldReturnNotFoundResponseGivenInvalidFeatureId(): void
    {
        $finder = $this->createMock(FeatureFinder::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn(2354356);
        $response = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->expects($this->once())
            ->method('createResponse')
            ->with(404, 'Route Not Found.')
            ->willReturn($response);

        $requestHandler = new GetFeature($finder, $responseFactory);
        $requestHandler->handle($request);
    }


    public function testItShouldReturnNotFoundResponse(): void
    {
        $finder = $this->createMock(FeatureFinder::class);
        $finder->expects($this->once())
            ->method('get')
            ->with('some_id')
            ->willThrowException($this->createMock(FeatureNotFoundException::class));
        $response = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->expects($this->once())
            ->method('createResponse')
            ->with(404, 'Route Not Found.')
            ->willReturn($response);
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn('some_id');

        $requestHandler = new GetFeature($finder, $responseFactory);
        $requestHandler->handle($request);
    }

    public function testItShouldHandleRequestAndPrepareGetFeatureResponse(): void
    {
        $feature = $this->createMock(Feature::class);
        $feature->expects($this->once())
            ->method('jsonSerialize')
            ->willReturn(['feature_id' => 'some_feature_id']);
        $finder = $this->createMock(FeatureFinder::class);
        $finder->expects($this->once())
            ->method('get')
            ->with('some_id')
            ->willReturn($feature);
        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->once())
            ->method('write')
            ->with('{"feature_id":"some_feature_id"}');
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('withAddedHeader')
            ->willReturn($response);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->expects($this->once())
            ->method('createResponse')
            ->willReturn($response);
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn('some_id');

        $requestHandler = new GetFeature($finder, $responseFactory);
        $requestHandler->handle($request);
    }
}
