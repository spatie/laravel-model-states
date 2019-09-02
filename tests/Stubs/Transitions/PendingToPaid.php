<?php

namespace Spatie\State\Tests\Stubs\Transitions;

use Spatie\State\Tests\Stubs\Payment;
use Spatie\State\Tests\Stubs\States\Paid;
use Spatie\State\Tests\Stubs\States\Pending;
use Spatie\State\Transition;

class PendingToPaid extends Transition
{
    public function __invoke(Payment $payment): Payment
    {
        $this->ensureInitialState($payment, Pending::class);

        $payment->setState(new Paid($payment));
        $payment->paid_at = time();

        return $payment;
    }
}
