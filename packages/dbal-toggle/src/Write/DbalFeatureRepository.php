<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle\Write;

use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use Pheature\Core\Toggle\Write\Feature;
use Pheature\Core\Toggle\Write\FeatureId;
use Pheature\Core\Toggle\Write\FeatureRepository;
use DateTimeImmutable;

use function json_encode;

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
            $this->connection->insert(
                self::TABLE,
                [
                'feature_id' => $feature->id(),
                'name' => $feature->id(),
                'enabled' => (int)$feature->isEnabled(),
                'strategies' => json_encode($feature->strategies(), JSON_THROW_ON_ERROR),
                'created_at' => $now->format('Y-m-d H:i:s'),
                ]
            );
            return;
        }

        $this->connection->update(
            self::TABLE,
            [
            'enabled' => (int)$feature->isEnabled(),
            'strategies' => json_encode($feature->strategies(), JSON_THROW_ON_ERROR),
            'updated_at' => $now->format('Y-m-d H:i:s'),
            ],
            [
            'feature_id' => $feature->id(),
            ]
        );
    }

    public function get(FeatureId $featureId): Feature
    {
        $featureData = $this->findFeature($featureId->value());
        if (null === $featureData) {
            throw new InvalidArgumentException(sprintf('Not feature found for given id %s', $featureId->value()));
        }

        return DbalFeatureFactory::createFromDbalRepresentation($featureData);
    }

    public function remove(FeatureId $featureId): void
    {
        $this->connection->delete(
            self::TABLE,
            [
            'feature_id' => $featureId->value()
            ]
        );
    }

    /**
     * @param string $id
     * @return array<string, string>|null
     * @throws \Doctrine\DBAL\Exception
     */
    private function findFeature(string $id): ?array
    {
        $table = self::TABLE;
        $sql = <<<SQL
            SELECT * FROM $table WHERE feature_id = :feature_id
        SQL;

        $statement = $this->connection->executeQuery($sql, ['feature_id' => $id]);
        /** @var array<string, string> $result */
        $result = $statement->fetchAssociative();

        return $result ?: null;
    }
}
