<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle;

use Pheature\Core\Toggle\Read\Segments;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\Identity;
use Pheature\Model\Toggle\Segment;
use PHPUnit\Framework\TestCase;

final class EnableByMatchingSegmentTest extends TestCase
{
    /** @dataProvider getSegmentsCriteria */
    public function testItShouldBeSatisfiedByMatchingSegment(array $criteria): void
    {
        $segments = new Segments(new Segment('users_from_barcelona', $criteria));

        $strategy = new EnableByMatchingSegment($segments);
        self::assertTrue($strategy->isSatisfiedBy(new Identity('some_id', $criteria)));
    }

    public function testItShouldNotBeSatisfiedByUnMatchingSegment(): void
    {
        $segments = new Segments(new Segment('users_from_barcelona', [
            'location' => 'barcelona',
        ]));

        $strategy = new EnableByMatchingSegment($segments);
        self::assertFalse($strategy->isSatisfiedBy(new Identity('some_id', [
            'location' => 'bilbo',
        ])));
    }

    public function testItShouldNotBeSatisfiedWithoutAnySegment(): void
    {
        $segments = new Segments();

        $strategy = new EnableByMatchingSegment($segments);
        self::assertFalse($strategy->isSatisfiedBy(new Identity('some_id', [
            'location' => 'bilbo',
        ])));
    }

    public function testItShouldNotBeSatisfiedWithoutAnyCriteria(): void
    {
        $segments = new Segments(new Segment('users_from_barcelona', []));

        $strategy = new EnableByMatchingSegment($segments);
        self::assertFalse($strategy->isSatisfiedBy(new Identity('some_id', [
            'location' => 'bilbo',
        ])));
    }

    public function testItShouldBeSerializedAsArray(): void
    {
        $segments = new Segments(new Segment('users_from_barcelona', [
            'location' => 'barcelona',
        ]));

        $strategy = new EnableByMatchingSegment($segments);
        self::assertSame([
            'type' => EnableByMatchingSegment::NAME,
            'segments' => [
                [
                    'id'  => 'users_from_barcelona',
                    'criteria' => [
                        'location' => 'barcelona',
                    ],
                ]
            ],
        ], $strategy->jsonSerialize());
    }

    public function getSegmentsCriteria(): array
    {
        return [
            [
                [
                    'location' => 'barcelona',
                ]
            ],
            [
                [
                    'top_buyers' => true,
                    'location' => 'madrid',
                ]
            ],
            [
                [
                    'free_shipping' => true,
                    'top_buyers' => false,
                    'location' => 'bilbo',
                ]
            ],
        ];
    }
}
