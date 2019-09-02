<?php

namespace Spatie\State\Tests\Dummy\States;

use Spatie\State\State;
use Spatie\State\Tests\Dummy\Payment;

abstract class PaymentState implements State
{
    /** @var \Spatie\State\Tests\Dummy\Payment */
    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    abstract public function color(): string;
}
