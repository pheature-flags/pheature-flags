# Symfony Bundles

## Toggle Bundle

Feature toggle implementation out of the box for our [Symfony Framework]() applications.

> [Checkout official repository](https://github.com/pheature-flags/symfony-toggle)

### Installation guide

Install it using [composer package manager](https://getcomposer.org/download/) in a working Symfony application.

```bash
composer require pheature/symfony-toggle
```

### Config

```yaml
# config/packages/pheature-flags.yaml
parameters:
  pheature.inmemory.config:
    feature_1:
      id: feature_1
      enabled: false
      strategies: [ ]
    feature_2:
      id: feature_2
      enabled: true
      strategies: [ ]
      
services:
  Pheature\InMemory\Toggle\InMemoryConfig:
    class: Pheature\InMemory\Toggle\InMemoryConfig
    arguments:
      - '%pheature.inmemory.config%'

  Pheature\InMemory\Toggle\InMemoryFeatureFactory:
    class: Pheature\InMemory\Toggle\InMemoryFeatureFactory

  Pheature\Core\Toggle\Read\FeatureFinder:
    class: Pheature\InMemory\Toggle\InMemoryFeatureFinder
    arguments:
      - '@Pheature\InMemory\Toggle\InMemoryConfig'
      - '@Pheature\InMemory\Toggle\InMemoryFeatureFactory'

  Pheature\Core\Toggle\Read\Toggle:
    class: Pheature\Core\Toggle\Read\Toggle
    arguments:
      - '@Pheature\Core\Toggle\Read\FeatureFinder'

  Pheature\Sdk\CommandRunner:
    class: Pheature\Sdk\CommandRunner
    arguments:
      - '@Pheature\Core\Toggle\Read\Toggle'

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
