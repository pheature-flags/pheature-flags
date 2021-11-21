# Laminas and Mezzio packages

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Type coverage][ico-psalm]][link-psalm]
[![Test Coverage][ico-coverage]][link-coverage]
[![Mutation testing badge][ico-mutant]][link-mutant]
[![Total Downloads][ico-downloads]][link-downloads]
[![Maintainability][ico-maintain]][link-maintain]

## Toggle package

Feature toggle implementation out of the box for our [Laminas](https://getlaminas.org/) or [Mezzio Framework](https://docs.mezzio.dev/) applications.

> [Checkout official repository](https://github.com/pheature-flags/mezzio-toggle)

### Installation guide

Install it using [composer package manager](https://getcomposer.org/download/) in a working Laminas / Mezzio application.

```bash
composer require pheature/mezzio-toggle
```

```php
# config/config.php
$aggregator = new ConfigAggregator([
    // ...
    \Pheature\Community\Mezzio\ToggleConfigProvider::class,
    // ...
```

Once we have the package installed in our project, we need to choose one of the available storage drivers:

### InMemory driver

The In-memory driver reads the feature toggles info from the config. It’s handy, for example, to *allow incomplete
and un-tested code paths to be shipped to production as latent code which may never be turned on*, as
described in the [release toggles section](https://martinfowler.com/articles/feature-toggles.html#CategoriesOfToggles)
from Martin Fowler’s article.

It has some drawbacks, like the requirement of modifying the config and warming up the config cache to change the
current status of a toggle.

#### Installation

We can install it using the [composer package manager](https://getcomposer.org/download/).

```bash
composer require pheature/inmemory-toggle
```

#### Configuration


```php
# config/autoload/pheature-flags.global.php
return [
    'pheature_flags' => [
        'driver' => 'inmemory',
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
    ],
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
```

#### Doctrine DBAL

The [Doctrine DBAL](https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/) library is required to use the 
[pheature/dbal-toggle](https://github.com/pheature-flags/dbal-toggle).

We can install it using the [composer package manager](https://getcomposer.org/download/):

```bash
composer require doctrine/dbal
```

Then, to configure it in our Laminas / Mezzio application we should define a `DoctrineConnectionFactory` in our project 
to create the Doctrine `Connection` instance with the connection config defined in our project:

```php
<?php
// config/autoload/mezzio.global.php
declare(strict_types=1);

return [
    // ...
    'doctrine' => [
        'dbname' => 'pheature_flags',
        'user' => 'root',
        'password' => 'root',
        'host' => 'mysql',
        'driver' => 'pdo_mysql',
    ],
    // ...
];
```

```php
<?php
// src/App/src/DoctrineConnectionFactory.php

declare(strict_types=1);

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

class DoctrineConnectionFactory
{
    public function __invoke(ContainerInterface $container): Connection
    {
        $doctrineConfig = $container->get('config')['doctrine'];

        return DriverManager::getConnection($doctrineConfig);
    }
}
```

```php
<?php
// config/autoload/dependencies.global.php
declare(strict_types=1);

return [
    // ...
    'dependencies' => [
        // ...
        'factories' => [
            \Doctrine\DBAL\Connection::class => \App\DoctrineConnectionFactory::class,
        ],
    ],
];
```

#### Laminas CLI

We will use the [Laminas CLI](https://docs.laminas.dev/laminas-cli/) to run our CLI command to  
initialize the database schema.

```bash
composer require laminas/laminas-cli
```

#### Configuration

```php
# config/autoload/pheature-flags.global.php
return [
    // ...
    'pheature_flags' => [
        'driver' => 'dbal',
    ],
    // ...
];
```

To initiate database schema, run the following command:

```bash
vendor/bin/laminas pheature:dbal:init-toggle
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

In this example, we are using a PSR-15 Request Handler, assuming that we already have a Security layer to our controller (*). 
We will use the InMemory driver with the previous configuration to show different view sections depending on the features 
enabled for the given identity of the user and the payload.

```php
<?php
// src/App/src/HomePageHandler.php
declare(strict_types=1);

namespace App;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Pheature\Core\Toggle\Read\Toggle;use Pheature\Model\Toggle\Identity;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HomePageHandler implements RequestHandlerInterface
{
    private TemplateRendererInterface $templating;
    private Toggle $toggle;

    public function __construct(TemplateRendererInterface $templating, Toggle $toggle)
    {
        $this->templating = $templating;
        $this->toggle = $toggle;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute('user');

        // Generate an identity based on any requirements
        if (null === $user) {
            $identity = new Identity('anon', [
                'location' => $request->getQueryParams()['location'] ?? 'unknown',
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

        $template = $this->templating->render('app::index', [
            'feature_section' => $isFeatureEnabled,
            'some_feature_section' => $isSomeFeatureEnabled,
            'in_progress_feature_section' => $isInProgressFeatureEnabled,
        ]);

        return new HtmlResponse($template);
    }
}
```

```php
<?php
// src/App/src/HomePageHandlerFactory.php
declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Pheature\Core\Toggle\Read\Toggle;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HomePageHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $template = $container->get(TemplateRendererInterface::class);
        $toggle   = $container->get(Toggle::class);

        return new HomePageHandler($template, $toggle);
    }
}
```

```twig
{# templates/app/index.html.twig #}
{% extends '@layout/default.html.twig' %}

{% block title %}Site A - Homepage{% endblock %}

{% block content %}
    {% if feature_section %}
        <div>
          <p>Showing "feature": <strong>enabled</strong></p>
        </div>
    {% else %}
        <div>
          <p>Showing "feature": <strong>disabled</strong></p>
        </div>
    {% endif %}

    {% if some_feature_section %}
        <div>
          <p>This section is only visible with "some_feature" enabled for request located in "barcelona"</p>
        </div>
    {% endif %}
    
    {% if in_progress_feature_section %}
        <div>
          <p>This is a work in progress section only visible with "in_progress_feature_section" 
            enabled for users with role "ROLE_DEVELOPER"</p>
        </div>      
    {% endif %}
{% endblock %}
```

(*) *The security layer isn’t required to use the Pheature flags library but highly recommended*

### CRUD PSR-7 API

The CRUD PSR-7 API package allows us to manage our feature configuration through HTTP endpoints.

#### Installation

We can install it using the [composer package manager](https://getcomposer.org/download/).

```bash
composer require pheature/toggle-crud-psr7-api
```

#### Configuration

To have the API routes available to our Laminas / Mezzio project, we need to enable them adding the following configuration:

```php
# config/autoload/pheature-flags.global.php
return [
    'pheature_flags' => [
        'api_enabled' => true,
        'api_prefix' => 'my-api-prefix', // It corresponds to each route path prefix
    ],
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
    <!-- Stylesheet -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/pheature-flags/toggle-ui@1.0.x/dist/toggle-ui.css" integrity="sha384-USNQle+pBi/Hf/ohoxPp3UcGs6L+t9DDXzbJ16zPEqWPA0cfc8A+LeVvav3zYvsG" crossorigin="anonymous">```
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
# config/autoload/pheature-flags.global.php
return [
    'pheature_flags' => [
        'driver' => 'inmemory', // One of "inmemory"; "dbal",
        'api_enabled' => false, // True to enable CRUD HTTP API
        'api_prefix' => '', // It corresponds to each route path prefix
        'segment_types' => [
            [
                'type' => 'identity_segment',
                'factory_id' => 'Pheature\Model\Toggle\SegmentFactory',
            ],
            [
                'type' => 'strict_matching_segment',
                'factory_id' => 'Pheature\Model\Toggle\SegmentFactory',
            ],
        ],
        'strategy_types' => [
            [
                'type' => 'enable_by_matching_segment',
                'factory_id' => 'Pheature\Model\Toggle\StrategyFactory',
            ],
            [
                'type' => 'enable_by_matching_identity_id',
                'factory_id' => 'Pheature\Model\Toggle\StrategyFactory',
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
            ]
        ],
    ]
];
```

[ico-version]: https://img.shields.io/packagist/v/pheature/mezzio-toggle.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/pheature/mezzio-toggle
[ico-downloads]: https://img.shields.io/packagist/dt/pheature/mezzio-toggle.svg?style=flat-square
[link-downloads]: https://packagist.org/packages/pheature/mezzio-toggle
[ico-psalm]: https://shepherd.dev/github/pheature-flags/mezzio-toggle/coverage.svg
[link-psalm]: https://shepherd.dev/github/pheature-flags/mezzio-toggle
[ico-coverage]: https://codecov.io/gh/pheature-flags/mezzio-toggle/branch/1.0.x/graph/badge.svg?token=DTQIQUZ106
[link-coverage]: https://codecov.io/gh/pheature-flags/mezzio-toggle
[ico-mutant]: https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fpheature-flags%2Fmezzio-toggle%2F1.0.x
[link-mutant]: https://dashboard.stryker-mutator.io/reports/github.com/pheature-flags/mezzio-toggle/1.0.x
[ico-maintain]: https://api.codeclimate.com/v1/badges/7bf71e6da5ed1f93ea07/maintainability
[link-maintain]: https://codeclimate.com/github/pheature-flags/mezzio-toggle/maintainability
