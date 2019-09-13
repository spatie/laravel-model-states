<?php

namespace Spatie\State\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Spatie\State\HasStates;
use Spatie\State\State;
use Spatie\State\Transition;

class StateConfigError extends Exception
{
    public static function unknownState(string $field, Model $model): StateConfigError
    {
        $modelClass = get_class($model);

        return new self("No state field found for {$modelClass}::{$field}, did you forget to provide a mapping in {$modelClass}::registerStates()?");
    }

    public static function fieldNotFound(string $stateClass, Model $model): StateConfigError
    {
        $modelClass = get_class($model);

        return new self("No state field was found for the state {$stateClass} in {$modelClass}, did you forget to provide a mapping in {$modelClass}::registerStates()?");
    }

    public static function fieldDoesNotExtendState(string $field, string $expectedStateClass, string $actualClass): StateConfigError
    {
        return new self("State field `{$field}` expects state to be of type `{$expectedStateClass}`, instead got `{$actualClass}`");
    }

    public static function doesNotExtendState(string $class): StateConfigError
    {
        return self::doesNotExtendBaseClass($class, State::class);
    }

    public static function doesNotExtendTransition(string $class): StateConfigError
    {
        return self::doesNotExtendBaseClass($class, Transition::class);
    }

    public static function doesNotExtendBaseClass(string $class, string $baseClass): StateConfigError
    {
        return new self("Class {$class} does not extend the `{$baseClass}` base class.");
    }

    public static function resolveTransitionNotFound(Model $model): StateConfigError
    {
        $modelClass = get_class($model);

        $trait = HasStates::class;

        return new self("The method `resolveTransition` was not found on model `{$modelClass}`, are you sure it uses the `{$trait} trait?`");
    }
}
