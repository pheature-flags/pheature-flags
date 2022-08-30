# Strategy Types

The [pheature/toggle-model](https://github.com/pheature-flags/toggle-model) package gives us three different built-in 
strategy policies.

## Build-in Strategies

* Enable by matching Identity Id:
The Identity-based matching toggles will be enabled when the given Identity id matches with some item inner segment 
criteria. These kinds of strategies are useful to develop permission-based toggles.

* Enable by matching segment: 
The segment-based matching toggles will be enabled when given identity criteria matches with available segments 
criteria. For example, it can be useful to implement release toggles based on the application environment.

* In Collection matching segment:
The in-collection matching segment will be enabled when given identity criteria matches with some item inner segment 
criteria. This kind of strategy allows for creating less verbose segment-based strategies than using 
enable-by-matching criteria strategy types.

> [Available examples](https://github.com/pheature-flags/pheature-flags/tree/1.0.x/examples)

## Custom Strategies

When the Built-in strategies don't fit our requirements we can extend the library functionality by adding custom 
strategies.

The only requirement is to implement the  [pheature/toggle-core](https://github.com/pheature-flags/toggle-core)'s
`Pheature\Core\Toggle\Read\ToggleStrategy` interface.

```php
<?php
/** @link */ https://github.com/pheature-flags/toggle-core/blob/1.0.x/src/Read/ToggleStrategy.php */
namespace Pheature\Core\Toggle\Read;
use JsonSerializable;

interface ToggleStrategy extends JsonSerializable
{
    /** Strategy identifier */
    public function id(): string;
    /** Strategy type name */
    public function type(): string;
    /** Specification */
    public function isSatisfiedBy(ConsumerIdentity $identity): bool;
    /** Array serialization */
    public function toArray(): array;
    /** Array serialization on json_encode */
    public function jsonSerialize(): array;
}
```

In addition we need a toggle strategy factory implementation, to be able to put our new strategy type in the package flow.

```php
<?php
/** @link */ https://github.com/pheature-flags/toggle-core/blob/1.0.x/src/Read/ToggleStrategyFactory.php */
namespace Pheature\Core\Toggle\Read;

interface ToggleStrategyFactory extends WithProcessableFixedTypes
{
    public function create(string $strategyId, string $strategyType, ?Segments $segments = null): ToggleStrategy;
}
```

> We will love to [hear about your experience](https://github.com/pheature-flags/pheature-flags/discussions) with 
custom strategies. Also, to reference community custom Strategies in the docs.
