<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Transition;

class CreatedToFailed extends Transition
{
    /** @var \Spatie\ModelStates\Tests\Dummy\Payment */
    private $payment;

    /** @var string */
    private $message;

    public function __construct(Payment $payment, string $message)
    {
        $this->payment = $payment;
        $this->message = $message;
    }

    public function canTransition(): bool
    {
        return $this->payment->state->equals(Created::class);
    }

    public function handle()
    {
        $payment = (new CreatedToPending($this->payment))->handle();

        $payment = (new PendingToFailed($payment, $this->message))->handle();

        return $payment;
    }
}
