<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use Spatie\ModelStates\Tests\Dummy\DummyState;
use Spatie\ModelStates\Tests\Dummy\ModelWithMultipleStates;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\PaymentWithAllowTransitions;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Tests\Dummy\States\Refunded;
use Spatie\ModelStates\Tests\Dummy\States\Pending;

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

        $this->expectException(CouldNotPerformTransition::class);

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
        $this->assertNull($payment->state->getTimestamp());
    }

    /** @test */
    public function transition_with_state_timestamp()
    {
        $payment = Payment::create([
            'state' => Paid::class,
        ]);

        $payment->state->transitionTo(Refunded::class);
        $this->assertTrue($payment->state->is(Refunded::class));
        $this->assertEquals($this->knownDate, $payment->state->getTimestamp());
        $this->assertEquals($this->knownDate, $payment->refunded_at);
    }

    /** @test */
    public function multiple_transitions_can_be_set_up_in_one_go()
    {
        $payment = PaymentWithAllowTransitions::create([
            'state' => Created::class,
        ]);

        $payment->state->transitionTo(Pending::class);
        $this->assertTrue($payment->state->is(Pending::class));

        $payment->state->transitionTo(Paid::class);
        $this->assertTrue($payment->state->is(Paid::class));
    }

    /** @test */
    public function transition_to_directly_on_the_model()
    {
        $payment = Payment::create([
            'state' => Pending::class,
        ]);

        $payment->transitionTo(Paid::class);
        $this->assertTrue($payment->state->is(Paid::class));
    }

    /** @test */
    public function transition_to_directly_on_the_model_throws_exception_when_there_are_multiple_state_fields()
    {
        $model = new ModelWithMultipleStates();

        $this->expectException(CouldNotPerformTransition::class);

        $model->transitionTo(DummyState::class);
    }

    /** @test */
    public function transition_to_directly_on_the_model_with_multiple_fields()
    {
        $model = new ModelWithMultipleStates();

        $model->transitionTo(DummyState::class, 'stateA');

        $this->assertInstanceOf(DummyState::class, $model->stateA);
    }
}
