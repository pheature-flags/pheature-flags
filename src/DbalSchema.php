<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle;

use Doctrine\DBAL\Schema\Schema;

final class DbalSchema
{
    private Schema $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function __invoke(): void
    {
        $table = $this->schema->createTable('pheature_toggles');
        $table->addColumn('feature_id', 'string', [
            'length' => 36,
        ]);
        $table->setPrimaryKey(['feature_id']);
        $table->addColumn('name', 'string', [
            'length' => 140,
        ]);
        $table->addColumn('enabled', 'boolean');
        if ('sqlite' === $this->platform->getName()) {
            $table->addColumn('strategies', 'json', [
                'default' => '[]'
            ]);
        } else {
            $table->addColumn('strategies', 'json');
        }
        $table->addColumn('created_at', 'datetime_immutable');
        $table->addIndex(['created_at']);
        $table->addColumn('updated_at', 'datetime_immutable', [
            'nullable' => true,
            'default' => null,
        ]);
    }
}
