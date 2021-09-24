# Laravel Packages

## Toggle package

Feature toggle implementation out of the box for our [Laravel Framework](https://laravel.com/) applications.

> [Checkout official repository](https://github.com/pheature-flags/laravel-toggle)

### Installation guide

Install it using [composer package manager](https://getcomposer.org/download/) in a working Laravel application.

```bash
composer require pheature/laravel-toggle
```

### InMemory driver

The In-memory driver reads the feature toggles info from the config. It’s handy, for example, to *allow incomplete
and un-tested code paths to be shipped to production as latent code which may never be turned on*, as
described in the [release toggles section](https://martinfowler.com/articles/feature-toggles.html#CategoriesOfToggles)
from Martin Fowler’s article.

It has some drawbacks, like the requirement of modifying the config and clearing up the config cache to change the
current status of a toggle.

#### Installation

We can install it using the [composer package manager](https://getcomposer.org/download/).

```bash
composer require pheature/inmemory-toggle
php artisan vendor:publish --provider="Pheature\Community\Laravel\ToggleProvider"
```

#### Configuration

```php
<?php
// config/pheature-flags.php
declare(strict_types=1);

use ...;

return [
    'driver' => 'inmemory',
    ...,
    'toggles' => [
        [
            'id' => 'feature',
            'enabled' => false,
        ],
        [
            'id' => 'some_feature',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_by_location',
                    'strategy_type' => 'enable_by_matching_segment',
                    'segments' => [
                        [
                            'segment_id' => 'requests_from_barcelona',
                            'segment_type' => 'strict_matching_segment',
                            'criteria' => ['location' => 'barcelona'],
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 'in_progress_feature',
            'enabled' => true,
            'strategies' => [
                [
                    'strategy_id' => 'rollout_for_developers',
                    'strategy_type' => 'enable_by_matching_segment',
                    'segments' => [
                        [
                            'segment_id' => 'developer_role',
                            'segment_type' => 'strict_matching_segment',
                            'criteria' => ['role' => 'developer'],
                        ]
                    ],
                ],
            ],
        ],
    ],
    ...,
];
```

> [pheature/inmemory-toggle](/packages/inmemory-toggle)

### DBAL Driver

The DBAL Driver allows the storage of feature toggles data into an SQL database, as well as a real-time update on
enabling or disabling a feature for specific segments or all of them.

#### Installation

We can install it using the [composer package manager](https://getcomposer.org/download/).

```bash
composer require pheature/dbal-toggle
php artisan vendor:publish --provider="Pheature\Community\Laravel\ToggleProvider"
```

#### Configuration

```php
<?php
// config/pheature-flags.php
declare(strict_types=1);

use ...;

return [
    'driver' => 'dbal',
    ...,
];
```

Be sure that Doctrine DBAL connection is available in the Laravel CI container, for example by adding the reference in 
a Service Provider:

```php
<?php
// app/Providers/AppServiceProvider.php
declare(strict_types=1);

namespace App\Providers;

use Doctrine\DBAL\Connection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Connection::class, function (): Connection {
            $connectionParams = [
                'dbname' => env('DB_DATABASE'),
                'user' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'host' => env('DB_HOST'),
                'driver' => 'pdo_' . env('DB_CONNECTION'),
            ];

            return \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
        });
    }
...
}
```

To initiate database schema, run the following command:

```bash
php artisan pheature:dbal:init-toggle
```

We can insert the following sample features into the database:

```sql
INSERT INTO `pheature_toggles` (
    feature_id, name, enabled, strategies, created_at
) VALUES (
   'feature','feature',0,'[]', NOW()
),(
   'show_contact_info','show_contact_info',1,'[{\"segments\": [{\"criteria\": {\"location\": \"barcelona\"}, \"segment_id\": \"barcelona\", \"segment_type\": \"strict_matching_segment\"}], \"strategy_id\": \"enable_for_location\", \"strategy_type\": \"enable_by_matching_segment\"}]', NOW()
),(
   'show_home_dynamic_catalog','show_home_dynamic_catalog',1,'[{\"segments\": [{\"criteria\": [{\"role\": \"developer\"}], \"segment_id\": \"developer_role\", \"segment_type\": \"strict_matching_segment\"}], \"strategy_id\": \"rollout_for_developers\", \"strategy_type\": \"enable_by_matching_segment\"}]', NOW()
);
```

> [pheature/dbal-toggle](/packages/dbal-toggle)

#### Usage

<span id="inmemory_driver_usage"></span>

In this example, we are using a Laravel Controller with [authentication enabled](https://laravel.com/docs/8.x/authentication).
We will use the previous configuration file to show different view sections depending on the features enabled for the 
given identity of the user and the payload.

```php
<?php
// app/Http/Controllers/HomePage.php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Pheature\Community\Laravel\Toggle;
use Pheature\Sdk\OnDisabledFeature;

final class HomePage extends Controller
{
    public function __construct(
        private Toggle $toggle
    ) {}

    public function __invoke(Request $request): Response
    {    
        $user = $this->getUser();
        // Generate an identity based on any requirements
        if (null === $user) {
            $identity = new Identity('anon', [
                'location' => $request->query->get('location') ?? 'unknown',
                'role' => 'IS_AUTHENTICATED_ANONYMOUSLY'
            ]);
        } else {
            $identity = new Identity($user->id(), [
                'location' => $user->location(),
                'role' => $user->role(),
            ]);
        }
    
        $isFeatureEnabled = $this->toggle->isEnabled('feature');
        $isSomeFeatureEnabled = $this->toggle->isEnabled('some_feature', $identity);
        $isInProgressFeatureEnabled = $this->toggle->isEnabled('in_progress_feature', $identity);

        return $this->render('home/index.html.twig', [
            'feature_section' => $isFeatureEnabled,
            'some_feature_section' => $isSomeFeatureEnabled,
            'in_progress_feature_section' => $isInProgressFeatureEnabled,
        ]);
    }
}
```

```blade
@if($feature_section)
    <div>
        <p>Showing "feature": <strong>enabled</strong></p>
    </div>
@else
    <div>
        <p>Showing "feature": <strong>disabled</strong></p>
    </div>
@endif

@if($some_feature_section)
<div>
    <p>This section is only visible with "some_feature" enabled for request located in "barcelona"</p>
</div>
@endif

@if($in_progress_feature_section)
<div>
    <p>This is a work in progress section only visible with "in_progress_feature_section"
        enabled for users with role "ROLE_DEVELOPER"</p>
</div>
@endif

```

### CRUD PSR-7 API

The CRUD PSR-7 API package allows us to manage our feature configuration through HTTP endpoints.

#### Installation

We can install it using the [composer package manager](https://getcomposer.org/download/).

```bash
composer require pheature/toggle-crud-psr7-api
php artisan vendor:publish --provider="Pheature\Community\Laravel\ToggleProvider"
```

#### Configuration

```php
<?php
// config/pheature-flags.php
declare(strict_types=1);

use ...;

return [
    'api_prefix' => 'api',
    'api_enabled' => true,
    ...,
];
```

#### Usage

See the [API documentation](https://api.pheatureflags.io) to check the available endpoints.

> [pheature/toggle-crud-psr7-api](/packages/toggle-crud-api)

### Toggle UI

We can use the existing Toggle-UI Typescript library to integrate a web-component-based management panel inside an
existent website admin section.

#### Installation

The faster way to install Toggle UI is using [jsdelivr](https://www.jsdelivr.com/) CDN.

```html
<head>
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,700&display=swap">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Noto+Sans|Noto+Serif:400,400i,700,700i&display=swap"
          type="text/css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

<!-- Script -->
<script src="https://cdn.jsdelivr.net/gh/pheature-flags/toggle-ui@1.0.x/dist/toggle-ui.min.js" integrity="sha384-OaUr9L0gVXqphQMBb1b11DbM3oGmb88euKW+/ItGgkdof31VSufANOrBwjhF63n4" crossorigin="anonymous"></script>
</body>

```

#### Usage

Add the web component inside the website HTML, pointing to the [CRUD HTTP API](#CRUD_HTTP_API). In the example below,
we are assuming that the API is up and running at http://127.0.0.1:3000.

> [pheature/toggle-ui](/packages/toggle-ui)

```html
<toggle-list api-url="http://127.0.0.1:3000"></toggle-list>
```

![Web component admin](https://github.com/pheature-flags/toggle-ui/raw/1.0.x/images/web-component-admin.png)

### Config Reference

```php
<?php
// config/pheature_flags.php
declare(strict_types=1);

use Pheature\Model\Toggle\EnableByMatchingIdentityId;
use Pheature\Model\Toggle\EnableByMatchingSegment;
use Pheature\Model\Toggle\IdentitySegment;
use Pheature\Model\Toggle\InCollectionMatchingSegment;
use Pheature\Model\Toggle\SegmentFactory;
use Pheature\Model\Toggle\StrategyFactory;
use Pheature\Model\Toggle\StrictMatchingSegment;

return [
    'api_prefix' => '',
    'api_enabled' => false,
    'driver' => 'dbal',
    'segment_types' => [
        [
            'type' => IdentitySegment::NAME,
            'factory_id' => SegmentFactory::class
        ],
        [
            'type' => StrictMatchingSegment::NAME,
            'factory_id' => SegmentFactory::class
        ],
        [
            'type' => InCollectionMatchingSegment::NAME,
            'factory_id' => SegmentFactory::class
        ]
    ],
    'strategy_types' => [
        [
            'type' => EnableByMatchingSegment::NAME,
            'factory_id' => StrategyFactory::class
        ],
        [
            'type' => EnableByMatchingIdentityId::NAME,
            'factory_id' => StrategyFactory::class
        ],
    ],
    // Toggle data for in-memory implementation
    'toggles' => [
        [
            'id' => '', // string
            'enabled' => true, // bool
            // Optional rollout strategies
            // If one of the enabled strategies returns true the feature should be enabled.
            'strategies' => [
                [
                    'strategy_id' => '', // string
                    'strategy_type' => '', // string
                    'segments' => [
                        // Each strategy has segments where if any of it matches against the given payload it should compute as true.
                        'segment_id' => '', // string
                        'segment_type' => '', // string
                        'criteria' => [], // mixed
                    ],
                ]
            ],
        ],
    ],
];

```
