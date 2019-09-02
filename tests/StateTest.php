<?php

namespace Spatie\State\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\State\Tests\Stubs\Payment;
use Spatie\State\Tests\Stubs\Transitions\CreatedToFailed;
use Spatie\State\Tests\Stubs\Transitions\PendingToFailed;

class StateTest extends TestCase
{
    /** @test */
    public function a()
    {
        $payment = new Payment();

        $payment = (new CreatedToFailed('Gefaaldt'))($payment);

        dd($payment->getState());
    }
}
