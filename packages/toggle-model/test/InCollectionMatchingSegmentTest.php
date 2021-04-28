<?php

namespace Pheature\Test\Model\Toggle;

use Generator;
use Pheature\Model\Toggle\InCollectionMatchingSegment;
use PHPUnit\Framework\TestCase;

class InCollectionMatchingSegmentTest extends TestCase
{
    private const SEGMENT_ID = 'a_segment_id';

    /** @dataProvider nonMatchingPayloads */
    public function testItShouldNotMatch(array $criteria, array $payload): void
    {
        $segment = new InCollectionMatchingSegment(self::SEGMENT_ID, $criteria);

        self::assertFalse($segment->match($payload));
    }

    /** @dataProvider matchingPayloads */
    public function testItShouldMatch(array $criteria, array $payload): void
    {
        $segment = new InCollectionMatchingSegment(self::SEGMENT_ID, $criteria);

        self::assertTrue($segment->match($payload));
    }

    public function nonMatchingPayloads(): Generator
    {
        yield 'no criteria present' => [
            'criteria' => [],
            'payload' => [
                'location' => 'girona',
            ]
        ];

        yield 'strict value comparison' => [
            'criteria' => [
                'location' => 'barcelona',
            ],
            'payload' => [
                'location' => 'girona',
            ]
        ];

        yield 'possible values comparison' => [
            'criteria' => [
                'location' => ['barcelona', 'girona'],
            ],
            'payload' => [
                'location' => 'bilbao',
            ]
        ];

        yield 'multiple fields comparison' => [
            'criteria' => [
                'location' => ['barcelona', 'girona'],
                'top_buyers' => true,
            ],
            'payload' => [
                'location' => 'girona',
                'top_buyers' => false,
            ]
        ];

        yield 'multiple fields with missing field comparison' => [
            'criteria' => [
                'location' => ['barcelona', 'girona'],
                'top_buyers' => true,
            ],
            'payload' => [
                'location' => 'girona',
            ]
        ];
    }

    public function matchingPayloads(): Generator
    {
        yield 'strict value comparison' => [
            'criteria' => [
                'location' => 'barcelona',
            ],
            'payload' => [
                'location' => 'barcelona',
            ]
        ];

        yield 'possible values comparison' => [
            'criteria' => [
                'location' => ['barcelona', 'girona'],
            ],
            'payload' => [
                'location' => 'girona',
            ]
        ];

        yield 'multiple fields comparison' => [
            'criteria' => [
                'location' => ['barcelona', 'girona'],
                'top_buyers' => true,
            ],
            'payload' => [
                'location' => 'girona',
                'top_buyers' => true,
            ]
        ];
    }
}
