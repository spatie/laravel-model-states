<?php

namespace Spatie\State\Tests\Dummy\States;

use Spatie\State\State;
use Spatie\State\Tests\Dummy\Payment;

abstract class PaymentState extends State
{
    protected Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    abstract public function color(): string;
}
