<?php

declare(strict_types=1);

namespace Pheature\Test\Community\Mezzio\Fixtures;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

final class TestContainerFactory
{
    public static function createWithEmptyConfiguration(): ContainerInterface
    {
        return self::create(['config' => []]);
    }

    public static function createWithPheatureFlagsConfiguration(): ContainerInterface
    {
        $pheatureFlagsConfig = ToggleConfiguration::create()['pheature_flags'];

        return self::create(['config' => ['pheature_flags' => $pheatureFlagsConfig]]);
    }

    public static function create(array $content = []): ContainerInterface
    {
        return new class ($content) implements ContainerInterface {
            private array $content;

            public function __construct(array $content = [])
            {
                $this->content = $content;
            }

            public function get(string $id)
            {
                if ($this->has($id)) {
                    return $this->content[$id];
                }

                $errorMessage = sprintf('Service "%s" not found in test container', $id);
                throw new class ($errorMessage) extends RuntimeException implements NotFoundExceptionInterface {
                };
            }

            public function has(string $id)
            {
                return array_key_exists($id, $this->content);
            }
        };
    }
}
