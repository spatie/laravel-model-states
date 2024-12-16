<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\IgnoreSameStateModelState\IgnoreSameStateModelAttributeState;

class TestModelIgnoresSameStateByAttribute extends TestModel
{
    protected $casts = [
        'state' => IgnoreSameStateModelAttributeState::class,
    ];
}
