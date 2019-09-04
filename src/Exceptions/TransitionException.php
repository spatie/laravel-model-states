<?php

namespace Spatie\State\Exceptions;

use Exception;

class TransitionException extends Exception
{
    public static function make(
        Stateful $stateful,
        string $initialState,
        string $newState,
        string $because = null
    ): TransitionException {
        $statefulName = get_class($stateful);

        $message = "Cannot change the state of {$statefulName} from $initialState to $newState";

        if ($because) {
            $message .= ": {$because}";
        }

        return new self($message);
    }
}
