<?php

namespace Spatie\State\Exceptions;

use Exception;

class UnknownState extends Exception
{
    public static function make(
        string $field,
        string $modelClass
    ): UnknownState {
        return new self("No state field found for {$modelClass}::{$field}, did you forget to provide a mapping in {$modelClass}::\$states?");
    }
}
