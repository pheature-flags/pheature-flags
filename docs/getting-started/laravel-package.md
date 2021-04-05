# Laravel Packages

## Toggle package

Feature toggle implementation out of the box for our [Laravel Framework]() applications.

> [Checkout official repository](https://github.com/pheature-flags/laravel-toggle)

### Installation guide

Install it using [composer package manager](https://getcomposer.org/download/) in a working Laravel application.

```bash
composer require pheature/laravel-toggle
```

### Config

```php
<?php
// config/pheature.php

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
    ]
];

```

### Usage

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Pheature\Community\Laravel\Toggle;
use Pheature\Model\Toggle\Identity;
use Pheature\Sdk\OnDisabledFeature;
use Pheature\Sdk\OnEnabledFeature;
use Pheature\Sdk\Result;

class Controller extends BaseController
{
    public function index()
    {
        /** @var Result $result */
        $result = Toggle::inFeature(
            'feature_1',
            new Identity('some_id'),
            OnEnabledFeature::make(fn($argument) => $argument, ['Feature Enabled!!!']),
            OnDisabledFeature::make(fn($argument) => $argument, ['Feature Disabled :-S'])
        );

        return response()->json(['greet' => $result->getData()]);
    }
}

```
