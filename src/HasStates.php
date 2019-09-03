<?php

namespace Spatie\State;

use InvalidArgumentException;

trait HasStates
{
    public function getAttribute($key)
    {
        if (! isset($this->states[$key])) {
            return parent::getAttribute($key);
        }

        $stateClass = $this->attributes[$key] ?? null;

        if (! $stateClass) {
            return null;
        }

        return new $stateClass($this);
    }

    public function setAttribute($key, $value)
    {
        if (! isset($this->states[$key])) {
            return parent::setAttribute($key, $value);
        }

        $expectedStateClassName = $this->states[$key];

        if (! is_a($value, $expectedStateClassName)) {
            $modelClassName = get_class($this);

            $actualStateClassName = is_object($value)
                ? get_class($value)
                : (string) $value;

            throw new InvalidArgumentException("Expected {$modelClassName}::{$key} to be of type {$expectedStateClassName}, instead got {$actualStateClassName}");
        }

        parent::setAttribute($key, get_class($value));
    }
}
