# Pheature Flags Date Segment Types

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Type coverage][ico-psalm]][link-psalm]
[![Test Coverage][ico-coverage]][link-coverage]
[![Mutation testing badge][ico-mutant]][link-mutant]
[![Maintainability][ico-mantain]][link-mantain]
[![Total Downloads][ico-downloads]][link-downloads]

Pheature flags DateTime Interval Based Segment Types

> [Checkout official repository](https://github.com/pheature-flags/datetime-interval-segment-types)

## Installation

```php
composer require pheature/datetime-interval-segment-types beste/clock
```

## Usage

Provides custom segment types based in time intervals.

* DateTime Interval Segment:
  The DateTime Interval Segment is used inside Enable By Segment Matching strategy type. It matches when it runs 
  during a predefined date time interval and timezone.

```php
<?php

/** @var Pheature\Core\Toggle\Read\Toggle $toggle */
// Day 2022-10-29 11:30:00
$toggle->isEnabled('default_strategy', new Identity('some_id', [])); // true
// Day 2022-12-29 11:30:01
$toggle->isEnabled('default_strategy', new Identity('some_id', [])); // false
// Day 2022-10-29 11:29:59
$toggle->isEnabled('default_strategy', new Identity('some_id', [])); // false

$config = [
    'toggles' => [
        'default_strategy' => [
            'id' => 'default_strategy',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_by_interval_matching_segment',
                    'strategy_type' => 'enable_by_matching_segment',
                    'segments' => [
                        [
                            'segment_id' => 'interval_segment',
                            'segment_type' => 'datetime_matching_segment',
                            'criteria' => [
                                'start_datetime' => '2022-10-29 11:30:00',
                                'end_datetime' => '2022-12-29 11:30:00',
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],
];
```

* DateTime Interval Strict Matching Segment:
  The DateTime Interval Strict Matching Segment is used inside Enable By Segment Matching strategy type. It matches 
  when it runs during a predefined date time interval, timezone, and exact matching Identity Criteria.

```php
<?php

/** @var Pheature\Core\Toggle\Read\Toggle $toggle */
// Day 2022-10-29 11:30:00
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'key' => 'value',
])); // true
// Day 2022-10-29 11:30:00
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'key' => 'other_value',
])); // false
// Day 2022-12-29 11:30:01
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'key' => 'value',
]); // false
// Day 2022-10-29 11:29:59
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'key' => 'value',
]); // false

$config = [
    'toggles' => [
        'default_strategy' => [
            'id' => 'default_strategy',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_by_interval_matching_segment',
                    'strategy_type' => 'enable_by_matching_segment',
                    'segments' => [
                        [
                            'segment_id' => 'interval_segment',
                            'segment_type' => 'datetime_strict_matching_segment',
                            'criteria' => [
                                'start_datetime' => '2022-10-29 11:30:00',
                                'end_datetime' => '2022-12-29 11:30:00',
                                'matches' => [
                                    'key' => 'value',
                                ]
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],
];
```

## Contributing

Your contributions are always welcome! Please have a look at the [contribution guidelines](/CONTRIBUTING.md) first.

## License

We really believe in the Open Source Software, we built our carers around it, and we feel that we need to return our
knowledge to the community. For this reason we release all our packages under [BSD-3-Clause licence](/LICENSE.md).

[ico-version]: https://img.shields.io/packagist/v/pheature/datetime-interval-segment-types.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/pheature/datetime-interval-segment-types
[ico-coverage]: https://codecov.io/gh/pheature-flags/datetime-interval-segment-types/branch/1.x.x/graph/badge.svg?token=mug7GCiwlh
[link-coverage]: https://codecov.io/gh/pheature-flags/datetime-interval-segment-types
[ico-mantain]: https://api.codeclimate.com/v1/badges/037f266affd939dd99f0/maintainability
[link-mantain]: https://codeclimate.com/github/pheature-flags/datetime-interval-segment-types/maintainability
[ico-downloads]: https://img.shields.io/packagist/dt/pheature/datetime-interval-segment-types.svg?style=flat-square
[link-downloads]: https://packagist.org/packages/pheature/datetime-interval-segment-types
[ico-psalm]: https://shepherd.dev/github/pheature-flags/datetime-interval-segment-types/coverage.svg
[link-psalm]: https://shepherd.dev/github/pheature-flags/datetime-interval-segment-types
[ico-mutant]: https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fpheature-flags%2Fdatetime-interval-segment-types%2F1.x.x
[link-mutant]: https://dashboard.stryker-mutator.io/reports/github.com/pheature-flags/datetime-interval-segment-types/1.x.x
