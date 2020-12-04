<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;

/**
 * @property \Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState state
 */
class TestModelWithDefault extends TestModel
{
    protected $casts = [
        'state' => ModelState::class,
    ];
}
