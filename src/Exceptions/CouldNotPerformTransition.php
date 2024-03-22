<?php

namespace Spatie\ModelStates\Exceptions;

use Exception;

class CouldNotPerformTransition extends Exception
{
    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \Spatie\ModelStates\Transition  $transitionClass
     * @return  CouldNotPerformTransition
     */
    public static function notAllowed($model, $transitionClass): CouldNotPerformTransition
    {
        $modelClass = get_class($model);

        $transitionClass = get_class($transitionClass);

        return TransitionNotAllowed::make($modelClass, $transitionClass);
    }

    /**
     * @param  string  $from
     * @param  string  $to
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return  CouldNotPerformTransition
     */
    public static function notFound(string $from, string $to, $model): CouldNotPerformTransition
    {
        $modelClass = get_class($model);

        return TransitionNotFound::make($from, $to, $modelClass);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return  CouldNotPerformTransition
     */
    public static function couldNotResolveTransitionField($model): CouldNotPerformTransition
    {
        $modelClass = get_class($model);

        return CouldNotResolveTransitionField::make($modelClass);
    }
}
