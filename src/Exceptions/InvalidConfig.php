<?php

namespace Spatie\ModelStates\Exceptions;

use Exception;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Transition;

class InvalidConfig extends Exception
{
    /**
     * @param  string  $fieldName
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return  InvalidConfig
     */
    public static function fieldNotFound(string $fieldName, $model): InvalidConfig
    {
        $modelClass = get_class($model);

        return new self("No field `{$fieldName}` was found in `{$modelClass}`, did you forget to provide a mapping in {$modelClass}::registerStates()?");
    }

    public static function fieldDoesNotExtendState(string $field, string $expectedStateClass, string $actualClass): InvalidConfig
    {
        return FieldDoesNotExtendState::make($field, $expectedStateClass, $actualClass);
    }

    public static function doesNotExtendState(string $class): InvalidConfig
    {
        return self::doesNotExtendBaseClass($class, State::class);
    }

    public static function doesNotExtendTransition(string $class): InvalidConfig
    {
        return self::doesNotExtendBaseClass($class, Transition::class);
    }

    public static function doesNotExtendBaseClass(string $class, string $baseClass): InvalidConfig
    {
        return ClassDoesNotExtendBaseClass::make($class, $baseClass);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return  InvalidConfig
     */
    public static function resolveTransitionNotFound($model): InvalidConfig
    {
        $modelClass = get_class($model);

        $trait = HasStates::class;

        return MissingTraitOnModel::make($modelClass, $trait);
    }
}
