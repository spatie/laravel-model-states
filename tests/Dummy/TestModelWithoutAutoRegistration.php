<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\ModelStatesWithoutAutoRegister\ModelStateWithoutAutoRegister;

class TestModelWithoutAutoRegistration extends TestModel
{
    protected $casts = [
        'state' => ModelStateWithoutAutoRegister::class,
    ];
}
