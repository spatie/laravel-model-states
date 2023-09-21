<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsState\AllowAllTransitionsState;

class TestModelAllowAllTransitions extends TestModel
{
    protected $casts = [
        'state' => AllowAllTransitionsState::class,
    ];
}
