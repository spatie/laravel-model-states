<?php

namespace Spatie\State\Tests\Dummy\States;

use Spatie\State\State;
use Spatie\State\Tests\Dummy\Payment;

abstract class PaymentState extends State
{
    protected $payment;

    public static $states =[
        Canceled::class,
        Created::class,
        Failed::class,
        Paid::class,
        Pending::class,
        PaidWithoutName::class,
    ];

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    abstract public function color(): string;
}
