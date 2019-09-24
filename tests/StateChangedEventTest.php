<?php

namespace Spatie\State\Tests;

use Illuminate\Support\Facades\Event;
use Spatie\State\Events\StateChanged;
use Spatie\State\Exceptions\CouldNotPerformTransition;
use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Paid;
use Spatie\State\Tests\Dummy\States\Pending;
use Spatie\State\Tests\Dummy\Transitions\PendingToPaid;

class StateChangedEventTest extends TestCase
{
    /** @test */
    public function state_changed_event_is_fired_after_transition_run()
    {
        Event::fake();

        $payment = new Payment();

        $payment->state = new Pending($payment);

        $original = $payment->state;

        try {
            $payment->state->transition(PendingToPaid::class);
        } catch (CouldNotPerformTransition $e) {
        }

        Event::assertDispatched(StateChanged::class);

        /** StateChanged $e */
        Event::assertDispatched(StateChanged::class, function ($e) use ($original, $payment) {
            return ($e->finalState == $payment->state) == ($e->initialState == $original);
        });
    }
}
