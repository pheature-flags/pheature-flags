# In-Memory Toggle

In-memory implementation for the Pheature toggle system data layer.

> [Checkout official repository](https://github.com/pheature-flags/inmemory-toggle)

## Installation guide

Install it using [composer package manager](https://getcomposer.org/download/).

```bash
composer require pheature/inmemory-toggle
```

## Usage

Check https://github.com/pheature-flags/pheature-flags/tree/1.0.x/examples for more examples.

```php
<?php

declare(strict_types=1);

use Pheature\Core\Toggle\Read\Toggle;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;

require '../vendor/autoload.php';

$config = [
    'toggles' => [
        'feature_1' => [
            'id' => 'feature_1',
            'enabled' => false,
            'strategies' => []
        ],
    ]
];

$toggle = new Toggle(new InMemoryFeatureFinder(
    new InMemoryConfig($config['toggles']),
    new InMemoryFeatureFactory()
));

if ($toggle->isEnabled('feature_1')) {
    echo 'The feature_1 is work in progress.' . PHP_EOL;
}

if (false === $toggle->isEnabled('feature_1')) {
    echo 'The old functionality to be changed when the feature_1 is ready.' . PHP_EOL;
}
```


