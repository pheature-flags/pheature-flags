<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Symfony\DependencyInjection;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Pheature\Community\Symfony\DependencyInjection\FeatureRepositoryFactoryPass;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Psr11\Toggle\ToggleConfig;
use PHPUnit\Framework\TestCase;

final class FeatureRepositoryFactoryPassTest extends TestCase
{
    public function testItShouldRegisterInMemoryFeatureRepositoryInContainer(): void
    {
        $compilerPass = new FeatureRepositoryFactoryPass();
        $container = TestContainerFactory::create($compilerPass);
        $container->register(ToggleConfig::class, ToggleConfig::class)->addArgument([
            'driver' => 'inmemory',
            'prefix' => '',
        ]);

        $featureRepositoryFactoryDefinition = $container->getDefinition(FeatureRepository::class);
        self::assertFalse($featureRepositoryFactoryDefinition->isAutowired());
        self::assertTrue($featureRepositoryFactoryDefinition->isLazy());

        $featureRepositoryFactory = $container->get(FeatureRepository::class);
        self::assertInstanceOf(FeatureRepository::class, $featureRepositoryFactory);
    }

    public function testItShouldRegisterDbalFeatureRepositoryInContainer(): void
    {
        touch('test.sqlite');
        $compilerPass = new FeatureRepositoryFactoryPass();
        $container = TestContainerFactory::create($compilerPass, 'dbal');
        $container->register(ToggleConfig::class, ToggleConfig::class)->addArgument([
            'driver' => 'dbal',
            'prefix' => '',
        ]);
        $container->register(Connection::class, Connection::class)
            ->setFactory([DriverManager::class, 'getConnection'])
            ->addArgument(['url' => 'sqlite:///test.sqlite']);
        $container->prependExtensionConfig('pheature_flags', ['driver' => 'dbal']);

        $featureRepositoryFactoryDefinition = $container->getDefinition(FeatureRepository::class);
        self::assertFalse($featureRepositoryFactoryDefinition->isAutowired());
        self::assertTrue($featureRepositoryFactoryDefinition->isLazy());

        $featureRepositoryFactory = $container->get(FeatureRepository::class);
        self::assertInstanceOf(FeatureRepository::class, $featureRepositoryFactory);
        unlink('test.sqlite');
    }
}
