<?php

namespace Spatie\State;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionProperty;

trait HasStates
{
    private static $stateFields = null;

    public static function bootHasStates(): void
    {
        $stateFields = self::resolveStateFields();

        foreach ($stateFields as $field => $stateType) {
            static::retrieved(function (Model $model) use ($field) {
                $stateClass = State::resolveStateClass($model->getAttribute($field));

                $model->{$field} = new $stateClass($model);
            });

            static::creating(function (Model $model) use ($field) {
                $model->setAttribute(
                    $field,
                    State::resolveStateName($model->{$field})
                );
            });

            static::saving(function (Model $model) use ($field) {
                $model->setAttribute(
                    $field,
                    State::resolveStateName($model->{$field})
                );
            });
        }
    }

    private static function resolveStateFields(): array
    {
        if (self::$stateFields !== null) {
            return self::$stateFields;
        }

        $reflection = new ReflectionClass(static::class);

        self::$stateFields = collect($reflection->getProperties())
            ->mapWithKeys(function (ReflectionProperty $property) {
                $docComment = $property->getDocComment();

                preg_match('/@var ([\w\\\\]+)/', $docComment, $matches);

                $stateType = $matches[1] ?? null;

                if (! is_subclass_of($stateType, State::class)) {
                    return [];
                }

                return [$property->getName() => $stateType];
            })
            ->filter()
            ->toArray();

        return self::$stateFields;
    }
}
