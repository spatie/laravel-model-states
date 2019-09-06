<?php

namespace Spatie\State\Tests\Dummy\Transitions;

use Spatie\State\Tests\Dummy\Dependency;
use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Transition;

class TransitionWithDependency extends Transition
{
    /** @var \Spatie\State\Tests\Dummy\Payment */
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function handle(Dependency $dependency)
    {
        $this->payment->dependency = $dependency;

        return $this->payment;
    }
}
