<?php

namespace Spatie\State;

abstract class State
{
    public function __toString(): string
    {
        return get_class($this);
    }
}
