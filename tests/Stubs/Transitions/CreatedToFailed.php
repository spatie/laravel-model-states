<?php

namespace Spatie\State\Tests\Stubs\Transitions;

use Spatie\State\Tests\Stubs\Payment;
use Spatie\State\Tests\Stubs\States\Created;
use Spatie\State\Transition;

class CreatedToFailed extends Transition
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function __invoke(Payment $payment)
    {
        $this->ensureInitialState($payment, Created::class);

        $payment = (new CreatedToPending())($payment);

        $payment = (new PendingToFailed($this->message))($payment);

        return $payment;
    }
}
