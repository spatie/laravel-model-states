<?php

namespace Spatie\ModelStates\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class CouldNotPerformTransition extends Exception
{
    public static function notAllowed(Model $model, $transitionClass): CouldNotPerformTransition
    {
        $modelClass = get_class($model);

        $transitionClass = get_class($transitionClass);

        return new self("The transition `{$transitionClass}` is not allowed on model `{$modelClass}` at the moment.");
    }

    public static function notFound(string $from, string $to, Model $model): CouldNotPerformTransition
    {
        $modelClass = get_class($model);

        return new self("Transition from `{$from}` to `{$to}` on model `{$modelClass}` was not found, did you forget to register it in `{$modelClass}::registerStates()`?");
    }

    public static function couldNotResolveTransitionField(Model $model)
    {
        $modelClass = get_class($model);

        return new self("You tried to invoke {$modelClass}::transitionTo() directly, though there are multiple state fields configured. Please use {$modelClass}->stateField->transitionTo() instead.");
    }
}
