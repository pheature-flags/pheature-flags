<?php

declare(strict_types=1);

namespace Pheature\Test\Dbal\Toggle;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Pheature\Dbal\Toggle\DbalSchema;
use PHPUnit\Framework\TestCase;

class DbalSchemaTest extends TestCase
{
    public function testItShouldCreateValidDatabaseSchema(): void
    {
        $dbPath = realpath(__DIR__) . '/test.sqlite';
        touch($dbPath);

        $connection = DriverManager::getConnection(['url' => 'sqlite:///' . $dbPath]);
        $schema = new DbalSchema($connection);
        $schema();

        $statement = $connection->executeQuery('SELECT `sql` FROM sqlite_master sm WHERE sm.name = "pheature_toggles"');

        $tableSchema = method_exists($statement, 'fetchOne') ? $statement->fetchOne() : $statement->fetch()['sql'];
        unlink($dbPath);

        $this->assertStringContainsString('CREATE TABLE pheature_toggles', $tableSchema);
        $this->assertStringContainsString('feature_id VARCHAR(36) NOT NULL', $tableSchema);
        $this->assertStringContainsString('name VARCHAR(140) NOT NULL', $tableSchema);
        $this->assertStringContainsString('enabled BOOLEAN NOT NULL', $tableSchema);
        $this->assertStringContainsString('strategies CLOB DEFAULT \'[]\' NOT NULL --(DC2Type:json)', $tableSchema);
        $this->assertStringContainsString('created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)', $tableSchema);
        $this->assertStringContainsString('updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)', $tableSchema);
        $this->assertStringContainsString('PRIMARY KEY(feature_id))', $tableSchema);
    }
}
