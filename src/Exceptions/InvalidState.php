<?php

namespace Spatie\State\Exceptions;

use InvalidArgumentException;

class InvalidState extends InvalidArgumentException
{
    public static function make(
        string $value,
        string $abstractStateClass
    ): InvalidState {
        return new self("No valid state class found for value {$value} extending {$abstractStateClass}, did you forget to provide a mapping in {$abstractStateClass}::\$states?");
    }
}
