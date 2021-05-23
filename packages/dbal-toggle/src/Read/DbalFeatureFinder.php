<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle\Read;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use InvalidArgumentException;
use Pheature\Core\Toggle\Read\Feature;
use Pheature\Core\Toggle\Read\FeatureFinder;

use function array_map;
use function is_array;
use function sprintf;

final class DbalFeatureFinder implements FeatureFinder
{
    private Connection $connection;
    private DbalFeatureFactory $featureFactory;

    public function __construct(Connection $connection, DbalFeatureFactory $featureFactory)
    {
        $this->connection = $connection;
        $this->featureFactory = $featureFactory;
    }

    public function get(string $featureId): Feature
    {
        $sql = <<<SQL
            SELECT * FROM pheature_toggles WHERE feature_id = :feature_id
        SQL;

        $statement = $this->connection->executeQuery($sql, ['feature_id' => $featureId]);

        /** @var array<string, array<string, mixed>|bool|string>|null $feature */
        $feature = $statement->fetchAssociative();
        if (false === is_array($feature)) {
            throw new InvalidArgumentException(sprintf('Not feature found for given id %s', $featureId));
        }

        return $this->featureFactory->create($feature);
    }

    /**
     * @return Feature[]
     * @throws \Doctrine\DBAL\Exception
     * @throws \JsonException
     */
    public function all(): array
    {
        $sql = <<<SQL
            SELECT * FROM pheature_toggles ORDER BY created_at DESC
        SQL;

        $statement = $this->connection->executeQuery($sql);

        $features = $this->fetchFeatures($statement);

        /** @psalm-var array<array<string, array<string, mixed>|bool|string>> $features */
        return array_map(
            /** @param array<string, array<string, mixed>|bool|string> $feature */
            fn(array $feature) => $this->featureFactory->create($feature),
            $features
        );
    }

    /**
     * @return array<array<string, array<string, mixed>|bool|string>>
     */
    private function fetchFeatures(Result $statement): array
    {
        /** @var array<array<string, array<string, mixed>|bool|string>> $result */
        $result = $statement->fetchAllAssociative();

        return $result;
    }
}
