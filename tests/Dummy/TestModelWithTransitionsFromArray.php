<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\States\ModelState;
use Spatie\ModelStates\Tests\Dummy\States\StateA;
use Spatie\ModelStates\Tests\Dummy\States\StateC;

class TestModelWithTransitionsFromArray extends TestModel
{
    public function registerStates(): void
    {
        $this
            ->addState('state', ModelState::class)
            ->allowTransitions([
                [StateA::class, StateC::class]
            ]);
    }
}
