<?php

declare(strict_types=1);

namespace Pheature\Test\InMemory\Toggle;

use Generator;
use InvalidArgumentException;
use Pheature\InMemory\Toggle\InMemoryConfig;
use PHPUnit\Framework\TestCase;

final class InMemoryConfigTest extends TestCase
{
    /** @dataProvider getInvalidConfig */
    public function testItShouldNotAllowCreatingInvalidConfig($config): void
    {
        $this->expectException(InvalidArgumentException::class);
        new InMemoryConfig($config);
    }

    public function getInvalidConfig(): Generator
    {
        yield 'Empty feature id' => [
            [
                []
            ]
        ];
        yield 'Invalid feature id' => [
            [
                [
                    'id' => false,
                    'enabled' => true,
                ],
            ]
        ];
        yield 'Empty feature status' => [
            [
                [
                    'id' => 'some_feature',
                ]
            ]
        ];
        yield 'Invalid feature status' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => 'true?',
                ]
            ]
        ];
        yield 'Invalid feature strategies' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => true,
                ]
            ]
        ];
        yield 'Empty strategy_id' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => [
                        []
                    ],
                ]
            ]
        ];
        yield 'Invalid strategy_id' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => [
                        [
                            'strategy_id' => null,
                            'strategy_type' => 'some_type',
                        ]
                    ],
                ]
            ]
        ];
        yield 'Empty strategy_type' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => [
                        [
                            'strategy_id' => 'some_strategy',
                        ]
                    ],
                ]
            ]
        ];
        yield 'Invalid strategy_type' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => [
                        [
                            'strategy_id' => 'some_strategy',
                            'strategy_type' => null,
                        ]
                    ],
                ]
            ]
        ];
        yield 'Invalid segments' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => [
                        [
                            'strategy_id' => 'some_strategy',
                            'strategy_type' => 'some_type',
                            'segments' => false
                        ]
                    ],
                ]
            ]
        ];
        yield 'Empty segment id' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => [
                        [
                            'strategy_id' => 'some_strategy',
                            'strategy_type' => 'some_type',
                            'segments' => [
                                [
                                    'segment_type' => 'some_type'
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ];
        yield 'Invalid segment id' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => [
                        [
                            'strategy_id' => 'some_strategy',
                            'strategy_type' => 'some_type',
                            'segments' => [
                                [
                                    'segment_id' => null,
                                    'segment_type' => 'some_type'
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ];
        yield 'Empty segment type' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => [
                        [
                            'strategy_id' => 'some_strategy',
                            'strategy_type' => 'some_type',
                            'segments' => [
                                [
                                    'segment_id' => 'some_id',
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ];
        yield 'Invalid segment type' => [
            [
                [
                    'id' => 'some_feature',
                    'enabled' => true,
                    'strategies' => [
                        [
                            'strategy_id' => 'some_strategy',
                            'strategy_type' => 'some_type',
                            'segments' => [
                                [
                                    'segment_id' => 'some_id',
                                    'segment_type' => false,
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ];
    }
}
