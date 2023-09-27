<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsStateWithNoRegisteredStates\AllowAllTransitionsStateWithNoRegisteredStates;

class TestModelAllowAllTransitionsWithNoRegisteredStates extends TestModel
{
    protected $casts = [
        'state' => AllowAllTransitionsStateWithNoRegisteredStates::class,
    ];
}
