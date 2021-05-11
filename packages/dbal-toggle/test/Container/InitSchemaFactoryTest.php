<?php

declare(strict_types=1);

namespace Pheature\Test\Dbal\Toggle\Container;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Pheature\Dbal\Toggle\Cli\InitSchema;
use Pheature\Dbal\Toggle\Container\InitSchemaFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class InitSchemaFactoryTest extends TestCase
{
    public function testItShouldCreateInstanceOfInitSchema(): void
    {
        $schema = $this->createConfiguredMock(AbstractSchemaManager::class, [
            'createSchema' => $this->createMock(Schema::class)
        ]);
        $platform = $this->createMock(AbstractPlatform::class);
        if (method_exists(Connection::class, 'createSchemaManager')) {
            $connection = $this->createConfiguredMock(Connection::class, [
                'createSchemaManager' => $schema,
                'getDatabasePlatform' => $platform,
            ]);
        } else {
            $connection = $this->createConfiguredMock(Connection::class, [
                'getSchemaManager' => $schema,
                'getDatabasePlatform' => $platform,
            ]);
        }
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with(Connection::class)
            ->willReturn($connection);
        $factory = new InitSchemaFactory();
        $initSchema = $factory($container);
        self::assertInstanceOf(InitSchema::class, $initSchema);
    }

}
