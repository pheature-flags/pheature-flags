# Symfony Bundles

## Toggle Bundle

Feature toggle implementation out of the box for our [Symfony Framework](https://symfony.com/) applications.

> [Checkout official repository](https://github.com/pheature-flags/symfony-toggle)

### Installation guide

Install it using [composer package manager](https://getcomposer.org/download/) in a working Symfony application.

```bash
composer require pheature/symfony-toggle
composer require pheature/inmemory-toggle
composer require pheature/php-sdk
```

```php
<?php
// config/bundles.php
return [
    ...,
    Pheature\Community\Symfony\PheatureFlagsBundle::class => ['all' => true],
];

```

### Config

```yaml
# config/packages/pheature-flags.yaml
pheature_flags:
  driver:               ~ # One of "inmemory"; "dbal"
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

### Usage

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Pheature\Sdk\CommandRunner;
use Pheature\Sdk\OnDisabledFeature;
use Pheature\Sdk\OnEnabledFeature;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractController
{
    private CommandRunner $toggle;

    public function __construct(CommandRunner $toggle)
    {
        $this->toggle = $toggle;
    }

    public function index(Request $request): Response
    {
        $result = $this->toggle->inFeature(
            'feature_1',
            $request->get('identity'),
            OnEnabledFeature::make(fn($argument) => $argument, ['Feature Enabled!!!']),
            OnDisabledFeature::make(fn($argument) => $argument, ['Feature Disabled :-S'])
        );

        return $this->json(['greet' => $result->getData()]);
    }
}

```
