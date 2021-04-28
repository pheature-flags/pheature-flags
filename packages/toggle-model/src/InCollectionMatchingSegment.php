<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Read\Segment;

class InCollectionMatchingSegment implements Segment
{
    public const NAME = 'in_collection_matching_segment';
    private string $id;
    /** @var array<string, mixed> */
    private array $criteria;

    /**
     * InCollectionMatchingSegment constructor.
     *
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

    public function criteria(): array
    {
        return $this->criteria;
    }

    public function match(array $payload): bool
    {
        if (empty($this->criteria)) {
            return false;
        }

        /** @var mixed $criterionValue */
        foreach ($this->criteria as $field => $criterionValue) {
            if (!array_key_exists($field, $payload)) {
                return false;
            }

            if (!$this->isAMatch($payload[$field], $criterionValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $payloadValue
     * @param mixed $criterionValue
     * @return bool
     */
    private function isAMatch($payloadValue, $criterionValue): bool
    {
        if (is_array($criterionValue)) {
            return in_array($payloadValue, $criterionValue, true);
        }

        return $payloadValue === $criterionValue;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'criteria' => $this->criteria,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
