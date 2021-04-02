<?php

declare(strict_types=1);

namespace Pheature\Test\Core\Toggle\Read;

use Pheature\Core\Toggle\Read\ConsumerIdentity;
use Pheature\Core\Toggle\Read\Feature;
use Pheature\Core\Toggle\Read\FeatureFinder;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Core\Toggle\Read\ToggleStrategies;
use Pheature\Core\Toggle\Read\ToggleStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ToggleTest extends TestCase
{
    private const FEATURE_ID = 'SomeFeatureId';
    /** @var FeatureFinder|MockObject */
    private FeatureFinder $finder;
    /** @var Toggle */
    private Toggle $toggle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->finder = $this->createMock(FeatureFinder::class);
        $this->toggle = new Toggle($this->finder);
    }

    /**
     * @param ToggleStrategy|MockObject[] $strategies
     * @dataProvider getStrategies
     */
    public function testItShouldNotBeEnabledWithNonApplicableStrategy(array $strategies): void
    {
        $identity = $this->createMock(ConsumerIdentity::class);
        foreach ($strategies as $key => $strategy) {
            $strategy->expects(static::once())
                ->method('isSatisfiedBy')
                ->with($identity);
        }
        $feature = $this->createMock(Feature::class);
        $feature->expects(static::once())
            ->method('isEnabled')
            ->willReturn(true);
        $feature->expects(static::once())
            ->method('strategies')
            ->willReturn(new ToggleStrategies(...$strategies));
        $this->finder->expects(static::once())
            ->method('get')
            ->with(self::FEATURE_ID)
            ->willReturn($feature);

        $this->assertFalse($this->toggle->isEnabled(self::FEATURE_ID, $identity));
    }

    public function testItShouldNotBeEnabledWithDisabledFeature(): void
    {
        $identity = $this->createMock(ConsumerIdentity::class);
        $feature = $this->createMock(Feature::class);
        $feature->expects(static::once())
            ->method('isEnabled')
            ->willReturn(false);
        $this->finder->expects(static::once())
            ->method('get')
            ->with(self::FEATURE_ID)
            ->willReturn($feature);

        $this->assertFalse($this->toggle->isEnabled(self::FEATURE_ID, $identity));
    }

    public function testItShouldBeEnabledWithEnabledFeature(): void
    {
        $identity = $this->createMock(ConsumerIdentity::class);
        $feature = $this->createMock(Feature::class);
        $feature->expects(static::once())
            ->method('isEnabled')
            ->willReturn(true);
        $feature->expects(static::once())
            ->method('strategies')
            ->willReturn(new ToggleStrategies());
        $this->finder->expects(static::once())
            ->method('get')
            ->with(self::FEATURE_ID)
            ->willReturn($feature);

        $this->assertTrue($this->toggle->isEnabled(self::FEATURE_ID, $identity));
    }

    /**
     * @param ToggleStrategy|MockObject[] $strategies
     * @dataProvider getStrategies
     */
    public function testItShouldBeEnabledWithApplicableStrategy(array $strategies): void
    {
        $identity = $this->createMock(ConsumerIdentity::class);
        $applicableStrategy = $this->createMock(ToggleStrategy::class);
        $applicableStrategy->expects(static::once())
            ->method('isSatisfiedBy')
            ->with($identity)
            ->willReturn(true);
        foreach ($strategies as $key => $strategy) {
            $strategy->expects(static::once())
                ->method('isSatisfiedBy')
                ->with($identity);
        }
        $strategies[] = $applicableStrategy;
        $feature = $this->createMock(Feature::class);
        $feature->expects(static::once())
            ->method('isEnabled')
            ->willReturn(true);
        $feature->expects(static::once())
            ->method('strategies')
            ->willReturn(new ToggleStrategies(...$strategies));
        $this->finder->expects(static::once())
            ->method('get')
            ->with(self::FEATURE_ID)
            ->willReturn($feature);

        $this->assertTrue($this->toggle->isEnabled(self::FEATURE_ID, $identity));
    }

    public function getStrategies(): array
    {
        return [
            '1 disabled strategy' => [
                [
                    $this->createMock(ToggleStrategy::class),
                ],
            ],
            '2 disabled strategies' => [
                [
                    $this->createMock(ToggleStrategy::class),
                    $this->createMock(ToggleStrategy::class),
                ],
            ],
            '3 disabled strategies' => [
                [
                    $this->createMock(ToggleStrategy::class),
                    $this->createMock(ToggleStrategy::class),
                    $this->createMock(ToggleStrategy::class),
                ],
            ],
        ];
    }
}
