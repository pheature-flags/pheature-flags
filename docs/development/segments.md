# Segment Types

The [pheature/toggle-model](https://github.com/pheature-flags/toggle-model) package gives us three different built-in
segment types to apply to a rollout strategy.

## Build-in Segment Types

* Identity Segment:
  The identity Segment is used inside Enable By Identity Matching strategy type. It matches some identity inside toggle
  criteria in runtime.

```php
<?php

/** @var Pheature\Core\Toggle\Read\Toggle $toggle */
$toggle->isEnabled('identity_based_default_strategy', new Identity('some_valid_id', [])); // true
$toggle->isEnabled('identity_based_default_strategy', new Identity('not_valid_id', [])); // false

$config = [
    'toggles' => [
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
    ],
];
```

* Strict Matching Segment:
  The Strict Matching Segment is used by Enable By Matching Segment strategy type. It matches with exact the same value
  inside Toggle criteria in runtime.

```php
<?php

/** @var Pheature\Core\Toggle\Read\Toggle $toggle */
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'location' => 'barcelona',
    'role' => 'visitor',
])); // true

$toggle->isEnabled('default_strategy', new Identity('some_id', [])); // false
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'role' => 'visitor',
])); // false
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'location' => 'barcelona',
])); // false
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'location' => 'madrid',    'role' => 'visitor',

    'role' => 'visitor',
])); // false

$config = [
    'toggles' => [
        'default_strategy' => [
            'id' => 'default_strategy',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_by_strict_matching_segment',
                    'strategy_type' => 'enable_by_matching_segment',
                    'segments' => [
                        [
                            'segment_id' => 'matching_segments',
                            'segment_type' => 'strict_matching_segment',
                            'criteria' => [
                                'location' => 'barcelona',
                                'role' => 'visitor',
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],
];
```

* In Collection matching segment:
  The in-collection matching segment will be enabled when given identity criteria matches with some item inner segment
  criteria. This kind of segment allows for creating less verbose segment-based strategies.

```php
<?php

/** @var Pheature\Core\Toggle\Read\Toggle $toggle */
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'location' => 'barcelona',
    'role' => 'visitor',
])); // true
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'role' => 'visitor',
])); // true
$toggle->isEnabled('default_strategy', new Identity('some_id', [
    'location' => 'barcelona',
])); // true

$toggle->isEnabled('default_strategy', new Identity('some_id', [])); // false

$config = [
    'toggles' => [
        'default_strategy' => [
            'id' => 'default_strategy',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_by_collection_matching_segment',
                    'strategy_type' => 'enable_by_matching_segment',
                    'segments' => [
                        [
                            'segment_id' => 'matching_segments',
                            'segment_type' => 'in_collection_matching_segment',
                            'criteria' => [
                                'location' => 'barcelona',
                                'role' => 'visitor',
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],
];
```

> [Available examples](https://github.com/pheature-flags/pheature-flags/tree/1.0.x/examples)

## Custom Segment Types

When the Built-in Segment Types don't fit our requirements we can extend the library functionality by adding custom
Segment Types.

> See also [Date Matching Segments](/packages/datetime-interval-segment-types)

It requires to implement the  [pheature/toggle-core](https://github.com/pheature-flags/toggle-core)'s
`Pheature\Core\Toggle\Read\Segment` interface.

```php
<?php
/** @link */ https://github.com/pheature-flags/toggle-core/blob/1.0.x/src/Read/Segment.php */
namespace Pheature\Core\Toggle\Read;
use JsonSerializable;

interface Segment extends JsonSerializable
{
    /** Segment identifier */
    public function id(): string;
    /** Segment type name */
    public function type(): string;
    /** Segment matching criteria */
    public function criteria(): array;
    /** Specification */
    public function match(array $payload): bool;
    /** Array serialization */
    public function toArray(): array;
    /** Array serialization on json_encode */
    public function jsonSerialize(): array;
}
```

In addition, we need a toggle strategy factory implementation to be able to put our new strategy type in the package
flow.

```php
<?php
/** @link */ https://github.com/pheature-flags/toggle-core/blob/1.0.x/src/Read/SegmentFactory.php */
namespace Pheature\Core\Toggle\Read;

interface SegmentFactory extends WithProcessableFixedTypes
{
    public function create(string $segmentId, string $segmentType, array $criteria): Segment;
}
```

And register it using the ChainSegmentFactory

```php
<?php
$toggle = new Toggle(
    new InMemoryFeatureFinder(
        new InMemoryConfig($config['toggles']),
        new InMemoryFeatureFactory(
            new ChainToggleStrategyFactory(
                new \Pheature\Core\Toggle\Read\ChainSegmentFactory(
                    new SuperCoolSegmentFactory(),
                    new \Pheature\Model\Toggle\SegmentFactory(),
                ),
                new \Pheature\Model\Toggle\StrategyFactory()
            )
        )
    )
);

```


> We will love to [hear about your experience](https://github.com/pheature-flags/pheature-flags/discussions) with
> custom Segment Types. Also, to reference community custom Segment Types in the docs.
