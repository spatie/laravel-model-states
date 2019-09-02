<?php

namespace Spatie\State\Tests\Stubs\Transitions;

use Spatie\State\Stateful;
use Spatie\State\Tests\Stubs\Payment;
use Spatie\State\Tests\Stubs\States\Failed;
use Spatie\State\Tests\Stubs\States\Pending;
use Spatie\State\Transition;

class PendingToFailed extends Transition
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function __invoke(Payment $payment): Stateful
    {
        $this->ensureInitialState($payment, Pending::class);

        $payment->setState(new Failed($payment));
        $payment->errored_at = time();
        $payment->error_message = $this->message;

        return $payment;
    }
}
