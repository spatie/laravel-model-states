<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Tests\Dummy\States\Pending;

class CanTransitionToTest extends TestCase
{
    /** @test */
    public function transitionable_states_with_fieldname()
    {
        $payment = Payment::create([
            'state' => Created::class,
        ]);

        $this->assertTrue($payment->state->canTransitionTo(Pending::class));
        $this->assertFalse($payment->state->canTransitionTo(Paid::class));
    }
}
