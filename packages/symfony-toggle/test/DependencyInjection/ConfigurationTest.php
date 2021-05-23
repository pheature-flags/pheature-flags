<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Symfony\DependencyInjection;

use Generator;
use Pheature\Community\Symfony\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    /** @dataProvider validConfigurations */
    public function testItShouldReturnAConfiguration(array $actualConfig, array $expectedConfig): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $actualConfiguration = $processor->processConfiguration($configuration, $actualConfig);

        self::assertEquals($expectedConfig, $actualConfiguration);
    }

    /** @dataProvider invalidConfigurations */
    public function testItShouldThrowAnExceptionForAnInvalidConfiguration(array $actualConfig): void
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $this->expectException(InvalidConfigurationException::class);
        $processor->processConfiguration($configuration, $actualConfig);
    }

    public function validConfigurations(): Generator
    {
        yield 'user does not define any config' => [
            'user config' => [],
            'expected config' => [
                'api_prefix' => '',
                'api_enabled' => false,
                'strategy_types' => [],
                'segment_types' => [],
                'toggles' => [],
            ]
        ];

        yield 'user defines only the api_prefix' => [
            'user config' => [
                'pheature_flags' => [
                    'api_prefix' => 'myapp',
                ],
            ],
            'expected config' => [
                'api_prefix' => 'myapp',
                'api_enabled' => false,
                'strategy_types' => [],
                'segment_types' => [],
                'toggles' => [],
            ]
        ];

        yield 'user defines a driver' => [
            'user config' => [
                'pheature_flags' => [
                    'driver' => 'dbal',
                ],
            ],
            'expected config' => [
                'api_prefix' => '',
                'api_enabled' => false,
                'driver' => 'dbal',
                'strategy_types' => [],
                'segment_types' => [],
                'toggles' => [],
            ]
        ];

        yield 'user define a strategy type' => [
            'user config' => [
                'pheature_flags' => [
                    'strategy_types' => [
                        [
                            'type' => 'my_strategy_type',
                            'factory_id' => "\A\Factory\Id",
                        ]
                    ],
                ],
            ],
            'expected config' => [
                'api_prefix' => '',
                'api_enabled' => false,
                'strategy_types' => [
                    [
                        'type' => 'my_strategy_type',
                        'factory_id' => "\A\Factory\Id",
                    ]
                ],
                'segment_types' => [],
                'toggles' => [],
            ]
        ];

        yield 'user defines a segment type' => [
            'user config' => [
                'pheature_flags' => [
                    'segment_types' => [
                        [
                            'type' => 'my_segment_type',
                            'factory_id' => "\A\Factory\Id",
                        ]
                    ],
                ],
            ],
            'expected config' => [
                'api_prefix' => '',
                'api_enabled' => false,
                'strategy_types' => [],
                'segment_types' => [
                    [
                        'type' => 'my_segment_type',
                        'factory_id' => "\A\Factory\Id",
                    ]
                ],
                'toggles' => [],
            ]
        ];

        yield 'user defines a complete toggle' => [
            'user config' => [
                'pheature_flags' => [
                    'toggles' => [
                        [
                            'id' => 'my_feature',
                            'enabled' => true,
                            'strategies' => [
                                [
                                    'strategy_id' => 'a_strategy_id',
                                    'strategy_type' => 'a_strategy_type',
                                    'segments' => [
                                        [
                                            'segment_id' => 'a_segment_id',
                                            'segment_type' => 'a_segment_type',
                                            'criteria' => [
                                                'some' => 'criterion'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                ],
            ],
            'expected config' => [
                'api_prefix' => '',
                'api_enabled' => false,
                'strategy_types' => [],
                'segment_types' => [],
                'toggles' => [
                    [
                        'id' => 'my_feature',
                        'enabled' => true,
                        'strategies' => [
                            [
                                'strategy_id' => 'a_strategy_id',
                                'strategy_type' => 'a_strategy_type',
                                'segments' => [
                                    [
                                        'segment_id' => 'a_segment_id',
                                        'segment_type' => 'a_segment_type',
                                        'criteria' => [
                                            'some' => 'criterion'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ];
    }

    public function invalidConfigurations(): Generator
    {
        yield 'user defines an invalid driver' => [
            'user config' => [
                'pheature_flags' => [
                    'driver' => 'invalid_driver'
                ],
            ]
        ];

        yield 'user defines a strategy_type with a wrong field of the strategy' => [
            'user config' => [
                'pheature_flags' => [
                    'strategy_types' => [
                        [
                            'wrong_key' => 'a_factory_id',
                        ]
                    ],
                ],
            ]
        ];

        yield 'user defines a segment_types with a wrong field of the segment' => [
            'user config' => [
                'pheature_flags' => [
                    'segment_types' => [
                        [
                            'wrong_key' => 'a_factory_id',
                        ]
                    ],
                ],
            ]
        ];

        yield 'user defines a toggle with a wrong field in the base toggle config' => [
            'user config' => [
                'pheature_flags' => [
                    'toggles' => [
                        [
                            'wrong field' => 'a_value'
                        ]
                    ],
                ],
            ]
        ];

        yield 'user defines a toggle with a wrong field in the toggle\'s strategy' => [
            'user config' => [
                'pheature_flags' => [
                    'toggles' => [
                        [
                            'strategies' => [
                                [
                                    'wrong field' => 'a_value'
                                ]
                            ]
                        ]
                    ],
                ],
            ]
        ];

        yield 'user defines a toggle with a wrong field in toggle\'s strategy segment' => [
            'user config' => [
                'pheature_flags' => [
                    'toggles' => [
                        [
                            'strategies' => [
                                [
                                    'segments' => [
                                        [
                                            'wrong field' => 'a_value'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                ],
            ]
        ];
    }
}
