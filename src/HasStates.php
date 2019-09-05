<?php

namespace Spatie\State;

use Illuminate\Database\Eloquent\Model;
use TypeError;

trait HasStates
{
    public static function bootHasStates(): void
    {
        $serialiseState = function (string $field, string $expectedStateClass) {
            return function (Model $model) use ($field, $expectedStateClass) {
                $value = $model->getAttribute($field);

                if ($value === null) {
                    return;
                }

                $stateClass = State::resolveStateClass($value);

                if (! is_subclass_of($stateClass, State::class)) {
                    throw new TypeError("State field `{$field}` values must extend from `" . State::class . "`, instead got `{$stateClass}`");
                }

                if (! is_subclass_of($stateClass, $expectedStateClass)) {
                    throw new TypeError("State field `{$field}` expects state to be of type `{$expectedStateClass}`, instead got `{$stateClass}`");
                }

                $model->setAttribute(
                    $field,
                    State::resolveStateName($value)
                );
            };
        };

        $unserialiseState = function (string $field) {
            return function (Model $model) use ($field) {
                $stateClass = State::resolveStateClass($model->getAttribute($field));

                $model->setAttribute(
                    $field,
                    new $stateClass($model)
                );
            };
        };

        foreach (self::resolveStateFields() as $field => $expectedStateClass) {
            static::retrieved($unserialiseState($field));
            static::created($unserialiseState($field));
            static::saved($unserialiseState($field));

            static::updating($serialiseState($field, $expectedStateClass));
            static::creating($serialiseState($field, $expectedStateClass));
            static::saving($serialiseState($field, $expectedStateClass));
        }
    }

    private static function resolveStateFields(): array
    {
        return (new static)->states ?? [];
    }
}
