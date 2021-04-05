# Getting started

Welcome to our getting started guide, here we will learn how to use Pheature Toggles as standalone PHP library.
Also we have Pheature Toggles available out of the box for the most popular PHP frameworks:

- [Laravel packages](/getting-started/laravel-package)
- [Symfony Bundles](/getting-started/symfony-bundle)
- [Laminas & Mezzio packages](/getting-started/laminas-and-mezzio-package)

## Standalone PHP application

We can use Pheature Flags in any PHP 7.4 or greater application. In this example we will use the most generic installation using the in memory toggle implementation.

### Installation guide

Install it using [composer package manager](https://getcomposer.org/download/) in a working PHP application.

```bash
composer require pheature/php-sdk
composer require pheature/inmemory-toggle
```

### Config

```php
<?php
// config/pheature-flags.php

declare(strict_types=1);

return [
    'pheature_flags' => [
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
        ]
    ],
];

```

### Usage

```php
<?php
// public/index.php
declare(strict_types=1);

use Pheature\Core\Toggle\Read\Toggle;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
use Pheature\Model\Toggle\Identity;
use Pheature\Sdk\CommandRunner;
use Pheature\Sdk\OnDisabledFeature;
use Pheature\Sdk\OnEnabledFeature;

$config = include 'config/pheature-flags.php'

$toggle = new CommandRunner(
    new Toggle(
        new InMemoryFeatureFinder(
            new InMemoryConfig($config['pheature-flags']['toggles']),
            new InMemoryFeatureFactory()
        )
    )
);

$result = $toggle->inFeature(
    'feature_1',
    new Identity('my_id'),
    OnEnabledFeature::make(static fn(string $argument) => $argument, ['Feature Enabled!!!']),
    OnDisabledFeature::make(static fn(string $argument) => $argument, ['Feature Disabled :-S'])
);

echo $result->getData(); // Feature Enabled!!!

$result = $toggle->inFeature(
    'feature_2',
    new Identity('my_id'),
    OnEnabledFeature::make(static fn(string $argument) => $argument, ['Feature Enabled!!!']),
    OnDisabledFeature::make(static fn(string $argument) => $argument, ['Feature Disabled :-S'])
);

echo $result->getData(); // Feature Disabled :-S

```
