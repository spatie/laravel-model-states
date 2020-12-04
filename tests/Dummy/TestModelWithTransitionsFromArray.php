<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateC;

class TestModelWithTransitionsFromArray extends TestModel
{
    public function registerStates(): void
    {
        $this
            ->addState('state', ModelState::class)
            ->allowTransitions([
                [StateA::class, StateC::class],
            ]);
    }
}
