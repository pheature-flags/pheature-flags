<?php

declare(strict_types=1);

namespace Pheature\Model\Toggle;

use Pheature\Core\Toggle\Read\ConsumerIdentity;
use Pheature\Core\Toggle\Read\Segment as ISegment;
use Pheature\Core\Toggle\Read\Segments;
use Pheature\Core\Toggle\Read\ToggleStrategy;

final class EnableByMatchingSegment implements ToggleStrategy
{
    public const NAME = 'enable_by_matching_segment';
    private string $id;
    private Segments $segments;

    public function __construct(string $id, Segments $segments)
    {
        $this->id = $id;
        $this->segments = $segments;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function type(): string
    {
        return self::NAME;
    }

    public function isSatisfiedBy(ConsumerIdentity $identity): bool
    {
        foreach ($this->segments->all() as $segment) {
            if ($segment->match($identity->payload())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, string|array>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => self::NAME,
            'segments' => array_map(
                static fn(ISegment $segment): array => $segment->toArray(),
                $this->segments->all()
            ),
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
