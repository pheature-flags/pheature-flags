<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Read\Segment;

final class StrictMatchingSegment implements Segment
{
    public const NAME = 'strict_matching_segment';
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

    public function type(): string
    {
        return self::NAME;
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

        /**
         * @var mixed $value
         */
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
            'type' => self::NAME,
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
