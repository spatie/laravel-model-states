<?php

namespace Spatie\State\Tests\Dummy\Transitions;

use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Created;
use Spatie\State\Transition;

class CreatedToFailed extends Transition
{
    /** @var \Spatie\State\Tests\Dummy\Payment */
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
