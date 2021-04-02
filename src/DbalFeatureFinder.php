<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle;

use Doctrine\DBAL\Connection;
use Pheature\Core\Toggle\Read\Feature;
use Pheature\Core\Toggle\Read\FeatureFinder;

final class DbalFeatureFinder implements FeatureFinder
{
    private Connection $connection;
    private DbalFeatureFactory $featureFactory;

    public function __construct(Connection $connection, DbalFeatureFactory $featureFactory)
    {
        $this->featureFactory = $featureFactory;
        $this->connection = $connection;
    }

    public function get(string $featureId): Feature
    {
        $sql = <<<SQL
            SELECT * FROM pheature_toggles WHERE feature_id = :feature_id
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('feature_id', $featureId);
        $statement->execute();

        $feature = $statement->fetchAssociative();

        return $this->featureFactory->create($feature);
    }

    public function all(): array
    {
        $sql = <<<SQL
            SELECT * FROM pheature_toggles ORDER BY created_at DESC
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $features = $statement->fetchAllAssociative();

        return array_map(fn(array $feature) => $this->featureFactory->create($feature), $features);
    }
}
