<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle;

use InvalidArgumentException;
use Pheature\Core\Toggle\Read\Segments;
use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\Identity;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\StrictMatchingSegment;
use PHPUnit\Framework\TestCase;

final class EnableByMatchingIdentityIdTest extends TestCase
{
    public function testItShouldOnlyAcceptIdentitySegments(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $segments = new Segments(new StrictMatchingSegment('some_segments', []));
        new EnableByMatchingIdentityId($segments);
    }

    public function testItShouldBeSatisfiedByMatchingIdentitySegment(): void
    {
        $segments = new Segments(new IdentitySegment('developers', [
            'some_id',
        ]));

        $strategy = new EnableByMatchingIdentityId($segments);
        self::assertTrue($strategy->isSatisfiedBy(new Identity('some_id')));
    }

    public function testItShouldNotBeSatisfiedWithoutAnyIdentitySegment(): void
    {
        $segments = new Segments();

        $strategy = new EnableByMatchingIdentityId($segments);
        self::assertFalse($strategy->isSatisfiedBy(new Identity('some_id', [
            'some_id',
        ])));
    }

    public function testItShouldNotBeSatisfiedByUnMatchingIdentitySegment(): void
    {
        $segments = new Segments(new IdentitySegment('developers', [
            'some_id',
        ]));

        $strategy = new EnableByMatchingIdentityId($segments);
        self::assertFalse($strategy->isSatisfiedBy(new Identity('some_other_id')));
    }

    public function testItShouldBeSerializedAsArray(): void
    {
        $segments = new Segments(new IdentitySegment('developers', [
            'some_id',
        ]));

        $strategy = new EnableByMatchingIdentityId($segments);
        self::assertSame([
            'type' => EnableByMatchingIdentityId::NAME,
            'segments' => [
                [
                    'id'  => 'developers',
                    'criteria' => ['some_id']
                ]
            ],
        ], $strategy->jsonSerialize());
    }
}
