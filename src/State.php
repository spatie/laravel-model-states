<?php

namespace Spatie\State;

use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\State\Exceptions\InvalidState;

abstract class State
{
    public static function make(string $name, ...$args): State
    {
        $stateClass = self::resolveStateClass($name);

        if (! is_subclass_of($stateClass, static::class)) {
            throw InvalidState::make($name, static::class);
        }

        return new $stateClass(...$args);
    }

    public static function resolveStateClass(string $name): string
    {
        return Relation::getMorphedModel($name) ?? $name;
    }

    public static function resolveStateName(?State $state): ?string
    {
        if (! $state) {
            return null;
        }

        $stateClass = get_class($state);

        $alias = array_search($stateClass, Relation::$morphMap);

        if ($alias) {
            return $alias;
        }

        return $stateClass;
    }

    public function getValue(): string
    {
        return static::resolveStateName($this);
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
