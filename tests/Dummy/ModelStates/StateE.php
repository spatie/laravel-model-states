<?php

namespace Spatie\ModelStates\Tests\Dummy\ModelStates;

class StateE extends ModelState
{
    public static function getMorphClass(): string
    {
        return '5';
    }
}
