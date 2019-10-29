<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Pending;

class TransitionableStatesTest extends TestCase
{
    /** @test */
    public function transitionable_states_with_fieldname()
    {
        $payment = Payment::create([
            'state' => Created::class,
        ]);

        $transitionableStates = $payment->state->transitionableStates();

        $this->assertEquals(
            $transitionableStates,
            [Pending::getMorphClass(), Failed::getMorphClass()]
        );
    }
}
