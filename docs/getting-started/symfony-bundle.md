# Symfony Bundles

## Toggle Bundle

Feature toggle implementation out of the box for our [Symfony Framework](https://symfony.com/) applications.

> [Checkout official repository](https://github.com/pheature-flags/symfony-toggle)

### Installation guide

We can install it using the [composer package manager](https://getcomposer.org/download/) in a working Symfony application.

```bash
composer require pheature/symfony-toggle
```

```php
<?php
// config/bundles.php
return [
    ...,
    Pheature\Community\Symfony\PheatureFlagsBundle::class => ['all' => true],
];

```

Once we have the bundle installed in our project, we need to choose one of the available storage drivers:

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

```yaml
# config/packages/pheature-flags.yaml
pheature_flags:
  driver: "inmemory"
  toggles:
    - { id: "feature", enabled: false }
    -
      id: "some_feature"
      enabled: true
      strategies:
        -
          strategy_id: "rollout_by_location"
          strategy_type: "enable_by_matching_segment"
          segments:
            -
              segment_id: "requests_from_barcelona"
              segment_type: "strict_matching_segment"
              criteria: { "location": "barcelona" }
    -
      id: "in_progress_feature"
      enabled: true
      strategies:
        -
          strategy_id: "rollout_for_developers"
          strategy_type: "enable_by_matching_segment"
          segments:
            -
              segment_id: "developer_role"
              segment_type: "strict_matching_segment"
              criteria: { "role": "developer" }

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

#### Configuration

```yaml
# config/packages/pheature-flags.yaml
pheature_flags:
  driver: "dbal"
services:
  Pheature\Dbal\Toggle\Cli\InitSchema:
    factory: [ Pheature\Dbal\Toggle\Container\InitSchemaFactory, 'create' ]
    arguments:
      $connection: '@doctrine.dbal.default_connection' # doctrine dbal connection should be available
```

To initiate database schema, run the following command:

```bash
bin/console pheature:dbal:init-toggle
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

In this example, we are using a Symfony Controller, assuming that we already have the
[Security component](https://symfony.com/doc/current/components/security.html)(*) installed. We will use the previous 
configuration file to show different view sections depending on the features enabled for the given identity of the 
user and the payload.


```php
<?php
// src/Controller/HomePage.php
declare(strict_types=1);

namespace App\Controller;

use Pheature\Core\Toggle\Read\Toggle;
use Pheature\Model\Toggle\Identity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePage extends AbstractController
{
    public function __construct(
        private Toggle $toggle
    ) {}

    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {    
        $user = $this->getUser();
        // Generate an identity based on any requirements
        if (null === $user) {
            $identity = new Identity('anon', [
                'location' => $request->query->get('location') ?? 'unknow',
                'role' => 'IS_AUTHENTICATED_ANONYMOUSLY'
            ]);
        } else {
            $identity = new Identity($user->id(), [
                'location' => $user->location(),
                'role' => $user->role()
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

```twig
{# templates/home/index.html.twig #}
{% extends 'base.html.twig' %}

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
          <p>This section onlyu visible with "some_feature" enabled for request locatd in "barcelona"</p>
        </div>
    {% endif %}
    
    {% if in_progress_feature_section %}
        <div>
          <p>This is a work in progress section only visible with "in_progress_feature_section" 
            enabled for users with "ROLE_DEVELOPER"</p>
        </div>      
    {% endif %}
{% endblock %}
```

(*) *The security component isn’t required to use the Pheature flags library but highly recommended*

### Toggle CRUD PSR-7 API

The CRUD PSR-7 API package allows us to manage our feature configuration through HTTP endpoints.

#### Installation

We can install it using the [composer package manager](https://getcomposer.org/download/).

```bash
composer require pheature/toggle-crud-psr7-api
```

To import the API routes to our Symfony project, we need to add the following lines:

```yaml
# config/routes.yaml
pheature_flags:
  resource: '@PheatureFlagsBundle/Resources/config/toggle_api/routes.yaml'
  prefix: '%pheature_flags_prefix%'
```

#### Configuration

```yaml
# config/packages/pheature-flags.yaml
pheature_flags:
  # ...
  api_enabled: true
  api_prefix: "my-api-prefix"
  # ...
```

#### Usage

See the [API documentation](https://api.pheatureflags.io) to check the available endpoints.

> [pheature/toggle-crud-psr7-api](/packages/toggle-crud-psr7-api)

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

```yaml
# config/packages/pheature-flags.yaml
pheature_flags:
  driver:               ~ # One of "inmemory"; "dbal"
  api_enabled:          false # True to enable CRUD HTTP API
  api_prefix:           ''
  segment_types:
    - { type: 'identity_segment', factory_id: 'Pheature\Model\Toggle\SegmentFactory' }
    - { type: 'strict_matching_segment', factory_id: 'Pheature\Model\Toggle\SegmentFactory' }
  strategy_types:
    - { type: 'enable_by_matching_segment', factory_id: 'Pheature\Model\Toggle\StrategyFactory' }
    - { type: 'enable_by_matching_identity_id', factory_id: 'Pheature\Model\Toggle\StrategyFactory' }

  # Toggle data for in-memory implementation
  toggles:
    -
      id:                   ~ # string
      # Kill Switch
      enabled:              ~ # bool
      # Optional rollout strategies
      # If One of the enabled strategies returns true the feature should be enabled.
      strategies:
        -
          strategy_id:          ~ # string
          strategy_type:        ~ # string
          segments:
            # Each strategy has segments where if any of it matches against the given payload it should compute as true.
            -
              segment_id:           ~ # string
              segment_type:         ~ # string
              criteria:             ~ # mixed

```
