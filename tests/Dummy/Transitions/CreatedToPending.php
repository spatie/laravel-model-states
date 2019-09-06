<?php

namespace Spatie\State\Tests\Dummy\Transitions;

use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Created;
use Spatie\State\Tests\Dummy\States\Pending;
use Spatie\State\Transition;

class CreatedToPending extends Transition
{
    /** @var \Spatie\State\Tests\Dummy\Payment */
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function canTransition(): bool
    {
        return $this->payment->state->equals(Created::class);
    }

    public function handle(): Payment
    {
        $this->payment->state = new Pending($this->payment);

        $this->payment->save();

        return $this->payment;
    }
}
