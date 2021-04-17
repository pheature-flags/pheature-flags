<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Read\ConsumerIdentity;

final class Identity implements ConsumerIdentity
{
    private string $id;
    /** @var array<string, mixed>  */
    private array $payload;

    /**
     * Identity constructor.
     * @param string $id
     * @param array<string, mixed> $payload
     */
    public function __construct(string $id, array $payload = [])
    {
        $this->id = $id;
        $this->payload = $payload;
    }

    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
