<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Spatie\ModelStates\Tests\Dummy\Dependency;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Transition;

class TransitionWithDependency extends Transition
{
    /** @var \Spatie\ModelStates\Tests\Dummy\Payment */
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
