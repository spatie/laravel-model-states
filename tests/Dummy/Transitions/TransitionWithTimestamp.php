<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Spatie\ModelStates\Tests\Dummy\PaymentWithNoneDefaultTransition;
use Spatie\ModelStates\Tests\Dummy\States\PaymentState;
use Spatie\ModelStates\Transition;

class TransitionWithTimestamp extends Transition
{
    /** @var \Spatie\ModelStates\Tests\Dummy\Payment */
    private $payment;

    /**
     * @var \Spatie\ModelStates\State
     */
    private $newState;

    public function __construct(PaymentWithNoneDefaultTransition $payment, string $message, PaymentState $newState)
    {
        $this->payment = $payment;
        $this->newState = $newState;
    }

    public function handle()
    {
        $payment = $this->payment;

        if ($this->newState->shouldSetTimestamp()) {
            $payment{$this->newState->getTimestampField()} = now();
        }

        $payment->state = $this->newState;

        return $payment;
    }
}
