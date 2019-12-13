<?php

namespace Spatie\ModelStates\Tests;

use Illuminate\Support\Facades\Event;
use Spatie\ModelStates\Events\StateChanged;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Pending;
use Spatie\ModelStates\Tests\Dummy\Transitions\PendingToPaid;

class StateChangedEventTest extends TestCase
{
    /** @test */
    public function state_changed_event_is_fired_after_transition_run()
    {
        Event::fake();

        $payment = new Payment();

        $payment->state = new Pending($payment);

        $original = $payment->state;

        $payment->state->transition(PendingToPaid::class);

        Event::assertDispatched(StateChanged::class);

        Event::assertDispatched(
            StateChanged::class,
            function (StateChanged $event) use ($original, $payment) {
                $this->assertEquals($original, $event->initialState);

                // @see https://github.com/spatie/laravel-model-states/issues/49
                // $this->assertEquals($payment->state, $event->finalState);

                $this->assertEquals($payment, $event->model);
                $this->assertInstanceOf(PendingToPaid::class, $event->transition);

                return true;
            }
        );
    }
}
