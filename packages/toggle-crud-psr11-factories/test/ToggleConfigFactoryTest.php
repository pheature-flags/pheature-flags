<?php

declare(strict_types=1);

namespace Pheature\Test\Crud\Psr11\Toggle;

use InvalidArgumentException;
use Pheature\Crud\Psr11\Toggle\ToggleConfigFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class ToggleConfigFactoryTest extends TestCase
{
    private const DEFAULT_CONFIG =[
        'pheature_flags' => [
            'driver' => 'inmemory',
            'api_prefix' => '',
            'api_enabled' => false,
            'segment_types' => [
                [
                    'type' => IdentitySegment::NAME,
                    'factory_id' => SegmentFactory::class
                ],
                [
                    'type' => StrictMatchingSegment::NAME,
                    'factory_id' => SegmentFactory::class
                ]
            ],
            'strategy_types' => [
                [
                    'type' => EnableByMatchingSegment::NAME,
                    'factory_id' => StrategyFactory::class
                ],
                [
                    'type' => EnableByMatchingIdentityId::NAME,
                    'factory_id' => StrategyFactory::class
                ]
            ],
            'toggles' => [],
        ]
    ];

    public function testItShouldThrowAnExceptionWhenNoConfigurationFoundInContainer(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"pheature_flags" configuration not found in container');
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn([]);

        (new ToggleConfigFactory())->__invoke($container);
    }

    public function testItShouldThrowAnExceptionWhenNoPheatureFlagsConfigurationFound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(['pheature_flags' => []]);

        (new ToggleConfigFactory())->__invoke($container);
    }

    public function testItCreatesAToggleConfig(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(self::DEFAULT_CONFIG);

        $actual = (new ToggleConfigFactory())->__invoke($container);

        self::assertInstanceOf(ToggleConfig::class, $actual);
    }
}
