<?php

namespace Spatie\State\Tests\Dummy\Transitions;

use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Paid;
use Spatie\State\Tests\Dummy\States\Pending;
use Spatie\State\Transition;

class PendingToPaid extends Transition
{
    public function __invoke(Payment $payment): Payment
    {
        $this->ensureInitialState($payment, Pending::class);

        $payment->state = new Paid($payment);
        $payment->paid_at = time();

        $payment->save();

        return $payment;
    }
}
