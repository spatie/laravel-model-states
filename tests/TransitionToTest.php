<?php

namespace Spatie\State\Tests;

use Spatie\State\Exceptions\TransitionError;
use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Failed;
use Spatie\State\Tests\Dummy\States\Paid;
use Spatie\State\Tests\Dummy\States\Pending;

class TransitionToTest extends TestCase
{
    /** @test */
    public function transition_from_one_to_another()
    {
        $payment = Payment::create();

        $payment->state->transitionTo(Pending::class);

        $this->assertTrue($payment->state->is(Pending::class));
    }

    /** @test */
    public function invalid_transition_throws_error()
    {
        $payment = Payment::create();

        $this->expectException(TransitionError::class);

        $payment->state->transitionTo(Paid::class);
    }

    /** @test */
    public function transition_with_extra_parameters()
    {
        $payment = Payment::create();

        $payment->state->transitionTo(Failed::class, 'message');

        $this->assertTrue($payment->state->is(Failed::class));
        $this->assertEquals('message', $payment->error_message);
    }

    /** @test */
    public function transition_without_explicit_transition_class()
    {
        $payment = Payment::create([
            'state' => Pending::class,
        ]);

        $payment->state->transitionTo(Paid::class);

        $this->assertTrue($payment->state->is(Paid::class));
    }
}
