<?php

namespace Spatie\State\Tests\Stubs\Transitions;

use Spatie\State\Tests\Stubs\Payment;
use Spatie\State\Tests\Stubs\States\Created;
use Spatie\State\Tests\Stubs\States\Pending;
use Spatie\State\Transition;

class CreatedToPending extends Transition
{
    public function __invoke(Payment $payment): Payment
    {
        $this->ensureInitialState($payment, Created::class);

        $payment->setState(new Pending($payment));

        return $payment;
    }
}
