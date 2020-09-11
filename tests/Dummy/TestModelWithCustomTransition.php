<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\States\ModelState;
use Spatie\ModelStates\Tests\Dummy\States\StateA;
use Spatie\ModelStates\Tests\Dummy\States\StateB;
use Spatie\ModelStates\Tests\Dummy\States\StateC;
use Spatie\ModelStates\Tests\Dummy\Transitions\CustomInvalidTransition;
use Spatie\ModelStates\Tests\Dummy\Transitions\CustomTransition;

class TestModelWithCustomTransition extends TestModel
{
    public function registerStates(): void
    {
        $this
            ->addState('state', ModelState::class)
            ->allowTransition(StateA::class, StateB::class, CustomTransition::class)
            ->allowTransition(StateA::class, StateC::class, CustomInvalidTransition::class);
    }
}
