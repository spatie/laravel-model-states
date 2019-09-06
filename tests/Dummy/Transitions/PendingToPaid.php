<?php

namespace Spatie\State\Tests\Dummy\Transitions;

use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Paid;
use Spatie\State\Tests\Dummy\States\Pending;
use Spatie\State\Transition;

class PendingToPaid extends Transition
{
    /** @var \Spatie\State\Tests\Dummy\Payment */
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function canTransition(): bool
    {
        return $this->payment->state->equals(Pending::class);
    }

    public function handle(): Payment
    {
        $this->payment->state = new Paid($this->payment);
        $this->payment->paid_at = time();

        $this->payment->save();

        return $this->payment;
    }
}
