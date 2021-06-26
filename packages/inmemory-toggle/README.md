# Pheature Flags <br><sub><sup>In Memory Toggle</sup></sub>

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Type coverage][ico-psalm]][link-psalm]
[![Test Coverage][ico-coverage]][link-coverage]
[![Mutation testing badge][ico-mutant]][link-mutant]
[![Maintainability][ico-mantain]][link-mantain]
[![Total Downloads][ico-downloads]][link-downloads]

## Installation

Describe package installation

```php
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

## Contributing

Your contributions are always welcome! Please have a look at the [contribution guidelines](./CONTRIBUTING.md) first.

## License

We really believe in the Open Source Software, we built our carers around it, and we feel that we need to return our
knowledge to the community. For this reason we release all our packages under [BSD-3-Clause licence](./LICENSE.md). 

[ico-version]: https://img.shields.io/packagist/v/pheature/inmemory-toggle.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/pheature/inmemory-toggle
[ico-code-quality]: https://img.shields.io/scrutinizer/g/pheature-flags/inmemory-toggle.svg?style=flat-square
[link-code-quality]: https://scrutinizer-ci.com/g/pheature-flags/inmemory-toggle/badges/coverage.png?b=1.0.x
[ico-coverage]: https://codecov.io/gh/pheature-flags/inmemory-toggle/branch/1.0.x/graph/badge.svg?token=DTQIQUZ106
[link-coverage]: https://codecov.io/gh/pheature-flags/inmemory-toggle
[ico-psalm]: https://shepherd.dev/github/pheature-flags/inmemory-toggle/coverage.svg
[link-psalm]: https://shepherd.dev/github/pheature-flags/inmemory-toggle
[link-mantain]: https://codeclimate.com/github/pheature-flags/inmemory-toggle/maintainability
[ico-mantain]: https://api.codeclimate.com/v1/badges/2fdbd4050f3a852b85bd/maintainability
[ico-downloads]: https://img.shields.io/packagist/dt/pheature/inmemory-toggle.svg?style=flat-square
[link-downloads]: https://packagist.org/packages/pheature/inmemory-toggle
[ico-mutant]: https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fpheature-flags%2Finmemory-toggle%2F1.0.x
[link-mutant]: https://dashboard.stryker-mutator.io/reports/github.com/pheature-flags/inmemory-toggle/1.0.x
