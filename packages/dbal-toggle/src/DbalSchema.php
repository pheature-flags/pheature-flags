<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema;

final class DbalSchema
{
    private Schema $schema;
    private AbstractPlatform $platform;
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        if (method_exists($connection, 'createSchemaManager')) {
            $this->schema = $connection->createSchemaManager()->createSchema();
        } else {
            /** @psalm-suppress DeprecatedMethod */
            $this->schema = $connection->getSchemaManager()->createSchema();
        }
        $this->platform = $connection->getDatabasePlatform();
        $this->connection = $connection;
    }

    public function __invoke(): void
    {
        $table = $this->schema->createTable('pheature_toggles');
        $table->addColumn(
            'feature_id',
            'string',
            [
            'length' => 36,
            ]
        );
        $table->setPrimaryKey(['feature_id']);
        $table->addColumn(
            'name',
            'string',
            [
            'length' => 140,
            ]
        );
        $table->addColumn('enabled', 'boolean');
        if ('sqlite' === $this->platform->getName()) {
            $table->addColumn(
                'strategies',
                'json',
                [
                'default' => '[]'
                ]
            );
        } else {
            $table->addColumn('strategies', 'json');
        }
        $table->addColumn('created_at', 'datetime_immutable');
        $table->addIndex(['created_at']);
        $table->addColumn(
            'updated_at',
            'datetime_immutable',
            [
            'notnull' => false,
            'default' => null,
            ]
        );

        $queries = $this->schema->toSql($this->platform);
        foreach ($queries as $query) {
            if (false !== strpos($query, 'pheature_toggles')) {
                $this->connection->executeQuery($query);
            }
        }
    }
}
