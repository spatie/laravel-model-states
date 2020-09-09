<?php

namespace Spatie\ModelStates\Tests;

namespace Spatie\ModelStates\Tests;

use Illuminate\Support\Facades\Event;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Paid;

class StateUpdatedEventTest extends TestCase
{
    /** @test */
    public function state_is_properly_unserialized_when_updated_event_is_dispatched()
    {
        Event::fake([UpdatedEvent::class]);
        Payment::observe([PaymentObserver::class]);

        $payment = Payment::create();

        $payment->update([
            'state' => Paid::class,
        ]);

        Event::assertDispatched(UpdatedEvent::class, function ($event) {
            return $event->state->is(Paid::class);
        });
    }
}

class UpdatedEvent
{
    public $state;

    public function __construct($state)
    {
        $this->state = $state;
    }
}

class PaymentObserver
{
    public function updated(Payment $payment)
    {
        event(new UpdatedEvent($payment->state));
    }
}
