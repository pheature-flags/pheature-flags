<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\DependencyInjection;

use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use Pheature\Dbal\Toggle\Write\DbalFeatureRepository;
use Pheature\InMemory\Toggle\InMemoryFeatureRepository;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class FeatureRepositoryFactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var array<array<mixed>> $pheatureFlagsConfig */
        $pheatureFlagsConfig = $container->getExtensionConfig('pheature_flags');
        $mergedConfig = array_merge(...$pheatureFlagsConfig);

        $this->checkRequiredPackages($mergedConfig);

        $repository = $container->register(FeatureRepository::class, FeatureRepository::class)
            ->setAutowired(false)
            ->setLazy(true)
            ->setFactory([FeatureRepositoryFactory::class, 'create'])
            ->addArgument(new Reference(ToggleConfig::class));

        if ('dbal' === $mergedConfig['driver']) {
            $repository->addArgument(new Reference(Connection::class));
        } else {
            $repository->addArgument(null);
        }
    }

    private function checkRequiredPackages(array $mergedConfig): void
    {
        switch ($mergedConfig['driver']) {
            case 'inmemory':
                if (!class_exists(InMemoryFeatureRepository::class, true)) {
                    throw new InvalidArgumentException('Run "composer require pheature/inmemory-toggle" to install InMemory feature storage.');
                }
                break;
            case 'dbal':
                if (!class_exists(DbalFeatureRepository::class, true)) {
                    throw new InvalidArgumentException('Run "composer require pheature/dbal-toggle" to install DBAL feature storage.');
                }
                break;
        }
    }
}
