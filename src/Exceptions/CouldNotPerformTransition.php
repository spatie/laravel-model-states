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

        return TransitionNotAllowed::make($modelClass, $transitionClass);
    }

    public static function notFound(string $from, string $to, Model $model): CouldNotPerformTransition
    {
        $modelClass = get_class($model);

        return TransitionNotFound::make($from, $to, $modelClass);
    }

    public static function couldNotResolveTransitionField(Model $model): CouldNotPerformTransition
    {
        $modelClass = get_class($model);

        return CouldNotResolveTransitionField::make($modelClass);
    }
}
