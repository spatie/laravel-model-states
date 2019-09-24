<?php

namespace Spatie\State\Tests;

use Illuminate\Support\Facades\Event;
use Spatie\State\Events\StateChanged;
use Spatie\State\Tests\Dummy\Payment;
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

        $payment->state->transition(PendingToPaid::class);

        Event::assertDispatched(StateChanged::class);

        Event::assertDispatched(
        StateChanged::class,
            function (StateChanged $event) use ($original, $payment) {
                $this->assertTrue($original === $event->initialState);
                $this->assertTrue($payment->state === $event->finalState);
                $this->assertTrue($payment === $event->model);
                $this->assertInstanceOf(PendingToPaid::class, $event->transition);

                return true;
            }
        );
    }
}
