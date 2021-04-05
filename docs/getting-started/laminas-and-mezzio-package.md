# Laminas and Mezzio packages

## Toggle package

Feature toggle implementation out of the box for our [Laminas]() or [Mezzio Framework]() applications.

> [Checkout official repository](https://github.com/pheature-flags/mezzio-toggle)

### Installation guide

Install it using [composer package manager](https://getcomposer.org/download/) in a working Laminas or Mezzio application.

```bash
composer require pheature/mezzio-toggle
```

### Config

```php
<?php
// config/autoload/pheature-flags.php

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
// src/App/src/TestRequestHandler.php
declare(strict_types=1);

namespace App;

use Laminas\Diactoros\Response\JsonResponse;
use Pheature\Model\Toggle\Identity;
use Pheature\Sdk\CommandRunner;
use Pheature\Sdk\OnDisabledFeature;
use Pheature\Sdk\OnEnabledFeature;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TestRequestHandler implements RequestHandlerInterface
{
    private CommandRunner $toggle;

    public function __construct(CommandRunner $toggle)
    {
        $this->toggle = $toggle;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $result = $this->toggle->inFeature(
            'feature_1',
            new Identity('my_id'),
            OnEnabledFeature::make(static fn(string $argument) => $argument, ['Feature Enabled!!!']),
            OnDisabledFeature::make(static fn(string $argument) => $argument, ['Feature Disabled :-S'])
        );

        return new JsonResponse(['greet' => $result->getData()]);
    }
}
```

```php
<?php
// src/App/src/TestRequestHandlerFactory.php
declare(strict_types=1);

namespace App;

use Pheature\Sdk\CommandRunner;
use Psr\Container\ContainerInterface;

final class TestRequestHandlerFactory
{
    public function __invoke(ContainerInterface $container): TestRequestHandler
    {
        return new TestRequestHandler(
            $container->get(CommandRunner::class)
        );
    }
}

```
