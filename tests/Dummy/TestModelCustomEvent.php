<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\Tests\Dummy\CustomEventModelState\CustomEventModelState;
use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;

class TestModelCustomEvent extends TestModel
{
    protected $casts = [
        'state' => CustomEventModelState::class,
    ];
}
