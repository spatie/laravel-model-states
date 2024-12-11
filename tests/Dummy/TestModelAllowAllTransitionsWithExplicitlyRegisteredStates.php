<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsStateWithExplicitlyRegisteredStates\AllowAllTransitionsStateWithExplicitlyRegisteredStates;

class TestModelAllowAllTransitionsWithExplicitlyRegisteredStates extends TestModel
{
    protected $casts = [
        'state' => AllowAllTransitionsStateWithExplicitlyRegisteredStates::class,
    ];
}
