<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr7\Toggle;

use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Toggle\Handler\AddStrategy;
use Pheature\Crud\Toggle\Handler\DisableFeature;
use Pheature\Crud\Toggle\Handler\EnableFeature;
use Pheature\Crud\Toggle\Handler\RemoveStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class PatchFeatureTest extends TestCase
{
    /** @var FeatureRepository|MockObject */
    private FeatureRepository $repository;
    /** @var MockObject|ResponseFactoryInterface */
    private ResponseFactoryInterface $responseFactory;
    private AddStrategy $addStrategy;
    private RemoveStrategy $removeStrategy;
    private EnableFeature $enableFeature;
    private DisableFeature $disableFeature;
    private PatchFeature $handler;

    public function testItShouldReturnNotFoundResponseGivenInvalidFeatureId(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn(2354356);
        $response = $this->createMock(ResponseInterface::class);
        $this->responseFactory->expects($this->once())
            ->method('createResponse')
            ->with(404, 'Route Not Found.')
            ->willReturn($response);

        $this->handler->handle($request);

    }

    /** @dataProvider getInvalidRequestData */
    public function testItShouldReturnBadRequestResponseWhenTheActionIsNotPresent($body): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn('some_id');
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($body);


        $response = $this->createMock(ResponseInterface::class);
        $this->responseFactory->expects($this->once())
            ->method('createResponse')
            ->with(400, 'Bad request.')
            ->willReturn($response);

        $this->handler->handle($request);
    }

    public function testItShouldHandleAddStrategyRequestAndReturnProcessedResponse(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn('some_id');
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'action' => 'add_strategy',
                'value' => [
                    'strategy_id' => 'some_strategy_id',
                    'strategy_type' => 'identity_matching_strategy',
                ],
            ]);

        $featureId = FeatureId::fromString('some_id');
        $feature = Feature::withId($featureId);
        $this->repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($feature);

        $response = $this->createMock(ResponseInterface::class);
        $this->responseFactory->expects($this->once())
            ->method('createResponse')
            ->with(202, 'Processed.')
            ->willReturn($response);

        $this->handler->handle($request);
    }

    public function testItShouldHandleRemoveStrategyRequestAndReturnProcessedResponse(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn('some_id');
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'action' => 'remove_strategy',
                'value' => ['strategy_id' => 'some_strategy_id'],
            ]);

        $featureId = FeatureId::fromString('some_id');
        $feature = Feature::withId($featureId);
        $this->repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($feature);

        $response = $this->createMock(ResponseInterface::class);
        $this->responseFactory->expects($this->once())
            ->method('createResponse')
            ->with(202, 'Processed.')
            ->willReturn($response);

        $this->handler->handle($request);
    }

    public function testItShouldHandleEnableFeatureRequestAndReturnProcessedResponse(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn('some_id');
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'action' => 'enable_feature',
            ]);

        $featureId = FeatureId::fromString('some_id');
        $feature = Feature::withId($featureId);
        $this->repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($feature);

        $response = $this->createMock(ResponseInterface::class);
        $this->responseFactory->expects($this->once())
            ->method('createResponse')
            ->with(202, 'Processed.')
            ->willReturn($response);

        $this->assertFalse($feature->isEnabled());
        $this->handler->handle($request);
        $this->assertTrue($feature->isEnabled());
    }

    public function testItShouldHandleDisableFeatureRequestAndReturnProcessedResponse(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('feature_id')
            ->willReturn('some_id');
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'action' => 'disable_feature',
            ]);

        $featureId = FeatureId::fromString('some_id');
        $feature = Feature::withId($featureId);
        $feature->enable();
        $this->repository->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(FeatureId::class))
            ->willReturn($feature);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($feature);

        $response = $this->createMock(ResponseInterface::class);
        $this->responseFactory->expects($this->once())
            ->method('createResponse')
            ->with(202, 'Processed.')
            ->willReturn($response);

        $this->assertTrue($feature->isEnabled());
        $this->handler->handle($request);
        $this->assertFalse($feature->isEnabled());
    }

    public function getInvalidRequestData(): array
    {
        return [
            'null request body' => [
                null,
            ],
            'object request body' => [
                new \StdClass(),
            ],
            'no action present in request body' => [
                ['feature_id' => 'some_id']
            ],
            'array type action present in request body' => [
                ['action' => []]
            ],
            'invalid value for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => []]
            ],
            'null value for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => null]
            ],
            'string value for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => 'hello world!!']
            ],
            'null strategy_id value for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => ['strategy_id' => null, 'strategy_type' => 'some_type']]
            ],
            'invalid strategy_id value for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => ['strategy_id' => [], 'strategy_type' => 'some_type']]
            ],
            'not strategy_id for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => ['strategy_type' => 'some_type']]
            ],
            'not strategy_type for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => ['strategy_id' => 'some_id']]
            ],
            'null strategy_type value for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => ['strategy_id' => 'some_id', 'strategy_type' => null]]
            ],
            'invalid strategy_type value for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => ['strategy_id' => 'some_id', 'strategy_type' => []]]
            ],
            'object strategy_type value for add_strategy action' => [
                ['action' => 'add_strategy', 'value' => ['strategy_id' => 'some_id', 'strategy_type' => new \StdClass()]]
            ],
            'invalid value for remove_strategy action' => [
                ['action' => 'remove_strategy', 'value' => []]
            ],
            'null value for remove_strategy action' => [
                ['action' => 'remove_strategy', 'value' => null]
            ],
            'null strategy_id value for remove_strategy action' => [
                ['action' => 'remove_strategy', 'value' => ['strategy_id' => null]]
            ],
            'invalid strategy_id value for remove_strategy action' => [
                ['action' => 'remove_strategy', 'value' => ['strategy_id' => []]]
            ],
        ];
    }

    protected function setUp(): void
    {
        $this->repository = $this->createMock(FeatureRepository::class);
        $this->responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $this->addStrategy = new AddStrategy($this->repository);
        $this->removeStrategy = new RemoveStrategy($this->repository);
        $this->enableFeature = new EnableFeature($this->repository);
        $this->disableFeature = new DisableFeature($this->repository);
        $this->handler = new PatchFeature(
            $this->addStrategy,
            $this->removeStrategy,
            $this->enableFeature,
            $this->disableFeature,
            $this->responseFactory
        );
    }
}
