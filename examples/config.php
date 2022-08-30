<?php

declare(strict_types=1);

return [
    'toggles' => [
        'feature_1' => [
            'id' => 'feature_1',
            'enabled' => true,
            'strategies' => [],
        ],
        'feature_2' => [
            'id' => 'feature_2',
            'enabled' => false,
            'strategies' => [],
        ],
        'release_toggle_based_on_environment' => [
            'id' => 'release_toggle_based_on_environment',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_by_environment',
                    'strategy_type' => 'enable_by_matching_segment',
                    'segments' => [
                        [
                            'segment_id' => 'available_identities',
                            'segment_type' => 'strict_matching_segment',
                            'criteria' => ['dev'],
                        ]
                    ]
                ],
            ],
        ],
        'identity_based_default_strategy' => [
            'id' => 'identity_based_default_strategy',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_by_identity',
                    'strategy_type' => 'enable_by_matching_identity_id',
                    'segments' => [
                        [
                            'segment_id' => 'available_identities',
                            'segment_type' => 'identity_segment',
                            'criteria' => [
                                'some_valid_id',
                                'other_valid_id',
                                'another_valid_id',
                            ],
                        ]
                    ],
                ],
            ],
        ],
        'super_cool_rollout_strategy' => [
            'id' => 'super_cool_rollout_strategy',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'is_always_true',
                    'strategy_type' => 'super_cool_rollout_strategy',
                    'segments' => [],
                ]
            ],
        ],
        'super_cool_segmented_rollout_strategy' => [
            'id' => 'super_cool_segmented_rollout_strategy',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_by_segment',
                    'strategy_type' => 'enable_by_matching_segment',
                    'segments' => [
                        [
                            'segment_id' => 'super_cool_segment_name',
                            'segment_type' => 'super_cool_segment',
                            'criteria' => ['ayo'],
                        ]
                    ],
                ]
            ],
        ],
    ],
];
