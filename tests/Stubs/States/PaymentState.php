<?php

namespace Spatie\State\Tests\Stubs\States;

use Spatie\State\State;
use Spatie\State\Tests\Stubs\Payment;

abstract class PaymentState implements State
{
    /** @var \Spatie\State\Tests\Stubs\Payment */
    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    abstract public function color(): string;
}
