<?php

declare(strict_types=1);

namespace Pheature\Dbal\Toggle\Container;

use Doctrine\DBAL\Connection;
use Pheature\Dbal\Toggle\Cli\InitSchema;
use Pheature\Dbal\Toggle\DbalSchema;
use Psr\Container\ContainerInterface;

final class InitSchemaFactory
{
    public function __invoke(ContainerInterface $container): InitSchema
    {
        /** @var Connection $connection */
        $connection = $container->get(Connection::class);

        return new InitSchema(
            new DbalSchema($connection)
        );
    }
}
