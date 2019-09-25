<?php

namespace Spatie\ModelStates\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Transition;

class InvalidConfig extends Exception
{
    public static function unknownState(string $field, Model $model): InvalidConfig
    {
        $modelClass = get_class($model);

        return new self("No state field found for {$modelClass}::{$field}, did you forget to provide a mapping in {$modelClass}::registerStates()?");
    }

    public static function fieldNotFound(string $stateClass, Model $model): InvalidConfig
    {
        $modelClass = get_class($model);

        return new self("No state field was found for the state {$stateClass} in {$modelClass}, did you forget to provide a mapping in {$modelClass}::registerStates()?");
    }

    public static function fieldDoesNotExtendState(string $field, string $expectedStateClass, string $actualClass): InvalidConfig
    {
        return new self("State field `{$field}` expects state to be of type `{$expectedStateClass}`, instead got `{$actualClass}`");
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
        return new self("Class {$class} does not extend the `{$baseClass}` base class.");
    }

    public static function resolveTransitionNotFound(Model $model): InvalidConfig
    {
        $modelClass = get_class($model);

        $trait = HasStates::class;

        return new self("The method `resolveTransition` was not found on model `{$modelClass}`, are you sure it uses the `{$trait} trait?`");
    }
}
