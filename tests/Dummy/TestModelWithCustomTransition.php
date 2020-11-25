<?php

namespace Spatie\ModelStates\Tests\Dummy;


use Spatie\ModelStates\Tests\Dummy\OtherModelStates\OtherModelState;

class TestModelWithCustomTransition extends TestModel
{
    protected $casts = [
        'state' => OtherModelState::class,
    ];
}
