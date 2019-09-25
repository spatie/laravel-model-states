<?php

namespace Spatie\ModelStates;

abstract class Transition
{
    public function canTransition(): bool
    {
        return true;
    }
}
