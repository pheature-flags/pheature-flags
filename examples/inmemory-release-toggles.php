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
 * "Release Toggles allow incomplete and un-tested codepaths to be shipped to production as latent code which may never
 * be turned on." Martin Fowler https://martinfowler.com/articles/feature-toggles.html
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

if ($toggle->isEnabled('feature_1')) {
    echo 'Already working feature_1' . PHP_EOL;
}

if ($toggle->isEnabled('feature_2')) {
    echo 'The feature_2 is work in progress.' . PHP_EOL;
}

if (false === $toggle->isEnabled('feature_2')) {
    echo 'The old functionality to be changed when the feature_2 is ready.' . PHP_EOL;
}

if (
    $toggle->isEnabled(
        'release_toggle_based_on_environment',
        new Identity('some_id', [$_SERVER['APP_ENV'] ?? ''])
    )
) {
    echo 'Feature enabled in dev environment.' . PHP_EOL;
} else {
    echo 'Try: APP_ENV=dev php inmemory-release-toggles.php' . PHP_EOL;
}
