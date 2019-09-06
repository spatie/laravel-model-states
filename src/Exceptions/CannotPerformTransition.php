<?php

namespace Spatie\State\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class CannotPerformTransition extends Exception
{
    public static function make(
        Model $model,
        $transition
    ): CannotPerformTransition {
        $modelName = get_class($model);

        $transition = get_class($transition);

        return new self("The transition `{$transition}` cannot be performed on model `{$modelName}` at the moment.");
    }
}
