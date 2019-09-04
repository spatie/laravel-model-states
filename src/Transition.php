<?php

namespace Spatie\State;

use Spatie\State\Exceptions\TransitionException;

abstract class Transition
{
    protected function ensureInitialState($stateful, string $newState): void
    {
        $initialState = $stateful->state;

        if (is_a($initialState, $newState)) {
            return;
        }

        throw TransitionException::make($stateful, $initialState, $newState);
    }
}
