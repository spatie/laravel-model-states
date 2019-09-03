<?php

namespace Spatie\State\Tests;

use InvalidArgumentException;
use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Created;
use Spatie\State\Tests\Dummy\States\Pending;
use Spatie\State\Tests\Dummy\Transitions\CreatedToPending;
use Spatie\State\Tests\Dummy\WrongState;

class StateTest extends TestCase
{
    /** @test */
    public function state_is_properly_serialized()
    {
        $payment = Payment::create();

        $this->assertInstanceOf(Created::class, $payment->state);
        $this->assertTrue(Created::class === $payment->attributesToArray()['state']);

        $payment->state = new Pending($payment);

        $payment->save();

        $this->assertInstanceOf(Pending::class, $payment->state);
    }

    /** @test */
    public function can_only_set_the_specified_state_type()
    {
        $payment = Payment::create();

        $this->expectException(InvalidArgumentException::class);

        $payment->state = new WrongState();
    }

    /** @test */
    public function transitions_can_be_performed()
    {
        $payment = Payment::create();

        $createdToPending = new CreatedToPending();

        $payment = $createdToPending($payment);
    }
}
