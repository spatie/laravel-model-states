<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Spatie\ModelStates\Transition;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Pending;

class CreatedToPending extends Transition
{
    /** @var \Spatie\ModelStates\Tests\Dummy\PaymentWithAllowTransitions|\Spatie\ModelStates\Tests\Dummy\Payment */
    private $payment;

    /**
     * @param \Spatie\ModelStates\Tests\Dummy\PaymentWithAllowTransitions|\Spatie\ModelStates\Tests\Dummy\Payment
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function canTransition(): bool
    {
        return $this->payment->state->equals(Created::class);
    }

    /**
     * @return \Spatie\ModelStates\Tests\Dummy\Payment|\Spatie\ModelStates\Tests\Dummy\PaymentWithAllowTransitions
     */
    public function handle()
    {
        $this->payment->state = new Pending($this->payment);

        $this->payment->save();

        return $this->payment;
    }
}
