<?php

namespace Spatie\ModelStates\Tests\Dummy\ModelStates;

class StateB extends ModelState
{
    public function jsonSerialize()
    {
        return ['name' => 'StateB'];
    }
}
