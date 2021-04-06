<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr7\Toggle;

use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

final class GetFeaturesTest extends TestCase
{
    public function testItShouldHandleRequestAndPrepareGetFeaturesResponse(): void
    {
        $finder = $this->createMock(FeatureFinder::class);
        $finder->expects($this->once())
            ->method('all')
            ->willReturn([['hello' => 'world']]);
        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->once())
            ->method('write')
            ->with('[{"hello":"world"}]');
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

        $requestHandler = new GetFeatures($finder, $responseFactory);
        $requestHandler->handle($request);
    }
}
