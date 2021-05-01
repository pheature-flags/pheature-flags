<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\DependencyInjection;

use Doctrine\DBAL\Connection;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\FeatureRepositoryFactory;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
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
}
