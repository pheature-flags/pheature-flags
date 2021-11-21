<?php

declare(strict_types=1);

use Pheature\Core\Toggle\Read\ChainToggleStrategyFactory;
use Pheature\Core\Toggle\Read\Toggle;
use Pheature\InMemory\Toggle\InMemoryConfig;
use Pheature\InMemory\Toggle\InMemoryFeatureFactory;
use Pheature\InMemory\Toggle\InMemoryFeatureFinder;
use Pheature\Sdk\CommandRunner;
use Pheature\Sdk\OnDisabledFeature;
use Pheature\Sdk\OnEnabledFeature;

require '../vendor/autoload.php';

/**
 * "Release Toggles allow incomplete and un-tested codepaths to be shipped to production as latent code which may never
 * be turned on." Martin Fowler https://martinfowler.com/articles/feature-toggles.html
 */

$config = include 'config.php';

$commandRunner = new CommandRunner(
    new Toggle(
        new InMemoryFeatureFinder(
            new InMemoryConfig($config['toggles']),
            new InMemoryFeatureFactory(
                new ChainToggleStrategyFactory(
                    new \Pheature\Model\Toggle\SegmentFactory(),
                    new \Pheature\Model\Toggle\StrategyFactory()
                )
            )
        )
    )
);

$feature1 = function (string $someInput): string {
    return sprintf('Already working feature_1 with %s loaded.', $someInput) . PHP_EOL;
};
$feature2 = new class() {
    function __invoke(string $input1, array $input2): string {
        $result = sprintf('Working the brand new feature_2 with %s.', $input1) . PHP_EOL;
        foreach ($input2 as $key => $value) {
            $result .= sprintf('Running %s %s parameter in feature_2.', $key, $value) . PHP_EOL;
        }
        return $result;
    }
};
$oldFeature2 = function (): string {
    return 'Already working old feature_2.' . PHP_EOL;
};

$result = $commandRunner->inFeature('feature_1', null, OnEnabledFeature::make($feature1, ['Custom input']));

echo $result->getData();

$result = $commandRunner->inFeature(
    'feature_2',
    null,
    OnEnabledFeature::make($feature2, ['Custom input', ['some' => 'other']]),
    OnDisabledFeature::make($oldFeature2)
);

echo $result->getData();
