<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Read\Segment as ISegment;

final class Segment implements ISegment
{
    private string $id;
    /** @var array<string, mixed> */
    private array $criteria;

    /**
     * Segment constructor.
     * @param string $id
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
        $match = false;

        /** @var mixed $value */
        foreach ($this->criteria as $key => $value) {
            if (array_key_exists($key, $payload) && $value === $payload[$key]) {
                $match = true;
                continue;
            }

            return false;
        }

        return $match;
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