<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\IgnoreSameStateModelState\IgnoreSameStateModelState;

class TestModelIgnoresSameState extends TestModel
{
    protected $casts = [
        'state' => IgnoreSameStateModelState::class,
    ];
}
