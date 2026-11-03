<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;

class TestModelWithNonClassCast extends TestModel
{
    protected $casts = [
        'state' => ModelState::class,
        'message' => 'stateCastingTestType',
    ];
}
