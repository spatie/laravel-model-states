<?php

namespace Spatie\State;

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
