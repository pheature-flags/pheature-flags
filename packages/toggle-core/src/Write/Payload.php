<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use function json_decode;

final class Payload
{
    /**
     * @var array<string, mixed>
     */
    private array $criteria;

    /**
     * @param array<string, mixed> $criteria
     */
    private function __construct(array $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @param array<string, mixed> $criteria
     * @return static
     */
    public static function fromArray(array $criteria): self
    {
        return new self($criteria);
    }

    public static function fromJsonString(string $jsonPayload): self
    {
        /**
         * @var array<string, mixed> $payload
        */
        $payload = json_decode($jsonPayload, true, 16, JSON_THROW_ON_ERROR);

        return new self($payload);
    }

    /**
     * @return array<string, mixed>
     */
    public function criteria(): array
    {
        return $this->criteria;
    }
}
