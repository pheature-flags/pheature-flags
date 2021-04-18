<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr7\Toggle;

use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr7\Toggle\PostFeature;
use Pheature\Crud\Toggle\Handler\CreateFeature;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class PostFeatureTest extends TestCase
{
    public function testItShouldReturnNotFoundResponseGivenInvalidFeatureId(): void
    {
        $featureRepository = $this->createMock(FeatureRepository::class);
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

        $handler = new PostFeature(new CreateFeature($featureRepository), $responseFactory);
        $handler->handle($request);
    }

    public function testItShouldHandleRequestAndReturnCreatedResponse(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn('some_id');

        $featureRepository = $this->createMock(FeatureRepository::class);
        $featureRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Feature::class));

        $response = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->expects($this->once())
            ->method('createResponse')
            ->with(201, 'Created.')
            ->willReturn($response);

        $handler = new PostFeature(new CreateFeature($featureRepository), $responseFactory);
        $handler->handle($request);
    }
}
