<?php

namespace Spatie\State;

abstract class Transition
{
    public function canTransition(): bool
    {
        return true;
    }
}
