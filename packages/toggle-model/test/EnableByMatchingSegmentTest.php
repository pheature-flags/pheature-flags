<?php

declare(strict_types=1);

namespace Pheature\Test\Model\Toggle;

use Pheature\Core\Toggle\Read\Segments;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\Identity;
use Pheature\Model\Toggle\StrictMatchingSegment;
use PHPUnit\Framework\TestCase;

final class EnableByMatchingSegmentTest extends TestCase
{
    private const STRATEGY_ID = 'an_strategy';

    /** @dataProvider getSegmentsCriteria */
    public function testItShouldBeSatisfiedByMatchingSegment(array $criteria): void
    {
        $segments = new Segments(new StrictMatchingSegment('users_from_barcelona', $criteria));

        $strategy = new EnableByMatchingSegment(self::STRATEGY_ID, $segments);
        self::assertTrue($strategy->isSatisfiedBy(new Identity('some_id', $criteria)));
        self::assertSame(self::STRATEGY_ID, $strategy->id());
        self::assertSame('enable_by_matching_segment', $strategy->type());
    }

    public function testItShouldNotBeSatisfiedByUnMatchingSegment(): void
    {
        $segments = new Segments(new StrictMatchingSegment('users_from_barcelona', [
            'location' => 'barcelona',
        ]));

        $strategy = new EnableByMatchingSegment(self::STRATEGY_ID, $segments);
        self::assertFalse($strategy->isSatisfiedBy(new Identity('some_id', [
            'location' => 'bilbo',
        ])));
    }

    public function testItShouldNotBeSatisfiedWithoutAnySegment(): void
    {
        $segments = new Segments();

        $strategy = new EnableByMatchingSegment(self::STRATEGY_ID, $segments);
        self::assertFalse($strategy->isSatisfiedBy(new Identity('some_id', [
            'location' => 'bilbo',
        ])));
    }

    public function testItShouldNotBeSatisfiedWithoutAnyCriteria(): void
    {
        $segments = new Segments(new StrictMatchingSegment('users_from_barcelona', []));

        $strategy = new EnableByMatchingSegment(self::STRATEGY_ID, $segments);
        self::assertFalse($strategy->isSatisfiedBy(new Identity('some_id', [
            'location' => 'bilbo',
        ])));
    }

    public function testItShouldBeSerializedAsArray(): void
    {
        $segments = new Segments(new StrictMatchingSegment('users_from_barcelona', [
            'location' => 'barcelona',
        ]));

        $strategy = new EnableByMatchingSegment(self::STRATEGY_ID, $segments);
        self::assertSame([
            'id' => 'an_strategy',
            'type' => EnableByMatchingSegment::NAME,
            'segments' => [
                [
                    'id'  => 'users_from_barcelona',
                    'type' => 'strict_matching_segment',
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
