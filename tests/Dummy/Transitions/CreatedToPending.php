<?php

namespace Spatie\State\Tests\Dummy\Transitions;

use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Created;
use Spatie\State\Tests\Dummy\States\Pending;
use Spatie\State\Transition;

class CreatedToPending extends Transition
{
    public function __invoke(Payment $payment): Payment
    {
        $this->ensureInitialState($payment, Created::class);

        $payment->state = new Pending($payment);

        return $payment;
    }
}
