<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle;

use Doctrine\DBAL\Connection;
use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\FeatureRepository;
use Pheature\Crud\Toggle\Model\Feature as WriteModelFeature;
use Safe\DateTimeImmutable;

final class DbalFeatureRepository implements FeatureRepository
{
    private const TABLE = 'pheature_toggles';
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Feature $feature): void
    {
        $now = new DateTimeImmutable();

        if (null === $this->findFeature($feature->id())) {
            $this->connection->insert(self::TABLE, [
                'feature_id' => $feature->id(),
                'enabled' => $feature->isEnabled(),
                'strategies' => $feature->strategies(),
                'created_at' => $now->format('Y-m-d H:i:s'),
            ]);
            return;
        }

        $this->connection->update(self::TABLE, [
            'enabled' => $feature->isEnabled(),
            'strategies' => $feature->strategies(),
            'updated_at' => $now->format('Y-m-d H:i:s'),
        ], [
            'feature_id' => $feature->id(),
        ]);
    }

    public function get(FeatureId $featureId): Feature
    {
        $featureData = $this->findFeature($featureId->value());

        return new WriteModelFeature(
            FeatureId::fromString($featureData['feature_id']),
            (bool)$featureData['enabled'],
            $featureData['strategies']
        );
    }

    public function remove(FeatureId $featureId): void
    {
        $this->connection->delete(self::TABLE, [
            'feature_id' => $featureId->value()
        ]);
    }

    private function findFeature(string $id): ?array
    {
        $table = self::TABLE;
        $sql = <<<SQL
            SELECT * FROM $table WHERE feature_id = :feature_id
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('feature_id', $id);
        $statement->execute();
        $result = $statement->fetchAssociative();

        return $result ? $result : null;
    }
}