<?php

declare(strict_types=1);

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
use Pheature\Model\Toggle\Identity;

require '../vendor/autoload.php';

/**
 * Permissioning Toggles: "These flags are used to change the features or product experience that certain users receive."
 * Pete Hodgson https://martinfowler.com/articles/feature-toggles.html
 */

$config = include 'config.php';

$toggle = new Toggle(
    new InMemoryFeatureFinder(
        new InMemoryConfig($config['toggles']),
        new InMemoryFeatureFactory(
            new ChainToggleStrategyFactory(
                new \Pheature\Model\Toggle\SegmentFactory(),
                new \Pheature\Model\Toggle\StrategyFactory()
            )
        )
    )
);

if ($toggle->isEnabled('identity_based_default_strategy', new Identity('some_valid_id', []))) {
    echo 'Yay you are watching this text because you are granted.' . PHP_EOL;
}
