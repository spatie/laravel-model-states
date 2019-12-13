<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Pending;
use Spatie\ModelStates\Transition;

class PendingToFailed extends Transition
{
    private Payment $payment;

    private string $message;

    public function __construct(Payment $payment, string $message)
    {
        $this->payment = $payment;
        $this->message = $message;
    }

    public function canTransition(): bool
    {
        return $this->payment->state->isOneOf(Pending::class);
    }

    public function handle(): Payment
    {
        $this->payment->state = new Failed($this->payment);
        $this->payment->failed_at = now();
        $this->payment->error_message = $this->message;

        $this->payment->save();

        return $this->payment;
    }
}
