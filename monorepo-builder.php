<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        __DIR__ . '/packages',
        __DIR__ . '/prototypes',
    ]);

    $parameters->set(Option::REPOSITORY, [
        __DIR__ . '/packages',
    ]);
    $parameters->set(Option::REPOSITORY, [
        __DIR__ . '/prototypes',
    ]);
};
