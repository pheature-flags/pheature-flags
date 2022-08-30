# Segment Types

The [pheature/toggle-model](https://github.com/pheature-flags/toggle-model) package gives us two different built-in
strategy policies.

## Build-in Segment Types

* Identity Segment:
  The identity Segment is used inside Enable By Identity Matching strategy type. It matches some identity inside toggle
  criteria in runtime.

* Strict Matching Segment:
  The Strict Matching Segment is used by Enable By Matching Segment strategy type. It matches with exact the same value
  inside Toggle criteria in runtime.

* In Collection matching segment:
  The in-collection matching segment will be enabled when given identity criteria matches with some item inner segment
  criteria. This kind of segment allows for creating less verbose segment-based strategies.

> [Available examples](https://github.com/pheature-flags/pheature-flags/tree/1.0.x/examples)

## Custom Segment Types

When the Built-in Segment Types don't fit our requirements we can extend the library functionality by adding custom
Segment Types.

It requires to implement the  [pheature/toggle-core](https://github.com/pheature-flags/toggle-core)'s
`Pheature\Core\Toggle\Read\Segment` interface.

```php
<?php
/** @link */ https://github.com/pheature-flags/toggle-core/blob/1.0.x/src/Read/Segment.php */
namespace Pheature\Core\Toggle\Read;
use JsonSerializable;

interface Segment extends JsonSerializable
{
    /** Segment identifier */
    public function id(): string;
    /** Segment type name */
    public function type(): string;
    /** Segment matching criteria */
    public function criteria(): array;
    /** Specification */
    public function match(array $payload): bool;
    /** Array serialization */
    public function toArray(): array;
    /** Array serialization on json_encode */
    public function jsonSerialize(): array;
}
```

In addition, we need a toggle strategy factory implementation to be able to put our new strategy type in the package
flow.

```php
<?php
/** @link */ https://github.com/pheature-flags/toggle-core/blob/1.0.x/src/Read/SegmentFactory.php */
namespace Pheature\Core\Toggle\Read;

interface SegmentFactory extends WithProcessableFixedTypes
{
    public function create(string $segmentId, string $segmentType, array $criteria): Segment;
}
```

> We will love to [hear about your experience](https://github.com/pheature-flags/pheature-flags/discussions) with
> custom Segment Types. Also, to reference community custom Segment Types in the docs.
