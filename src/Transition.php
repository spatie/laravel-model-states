<?php

namespace Spatie\State;

use Exception;

abstract class Transition
{
    protected function ensureInitialState(Stateful $stateful, string $stateClass): void
    {
        if (is_a($stateful->getState(), $stateClass)) {
            return;
        }

        throw new Exception();
    }
}
