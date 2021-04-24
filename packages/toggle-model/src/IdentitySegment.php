<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Read\Segment;

final class IdentitySegment implements Segment
{
    private string $id;
    /**
     * @var array<string, mixed>
     */
    private array $criteria;

    /**
     * Segment constructor.
     *
     * @param string               $id
     * @param array<string, mixed> $criteria
     */
    public function __construct(string $id, array $criteria)
    {
        $this->id = $id;
        $this->criteria = $criteria;
    }

    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function criteria(): array
    {
        return $this->criteria;
    }

    public function match(array $payload): bool
    {
        if (false === array_key_exists('identity_id', $payload)) {
            return false;
        }

        /** @var string $identityId */
        $identityId = $payload['identity_id'];

        return in_array($identityId, $this->criteria, true);
    }

    /**
     * @return array<string, string|array>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'criteria' => $this->criteria,
        ];
    }

    /**
     * @return array<string, string|array>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
