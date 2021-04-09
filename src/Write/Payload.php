<?php

declare(strict_types=1);

namespace Pheature\Core\Toggle\Write;

use InvalidArgumentException;

use function json_decode;

final class Payload
{
    /** @var array<string, mixed> */
    private array $data;

    /**
     * @param array<string, mixed> $data
     */
    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fromJsonString(string $jsonPayload): self
    {
        /** @var array<string, mixed> $payload */
        $payload = json_decode($jsonPayload, true, 16, JSON_THROW_ON_ERROR);

        return new self($payload);
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }
}
