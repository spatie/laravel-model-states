<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\DummyState;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Pending;
use Spatie\ModelStates\Tests\Dummy\States\Canceled;
use Spatie\ModelStates\Tests\Dummy\ModelWithMultipleStates;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use Spatie\ModelStates\Tests\Dummy\PaymentWithAllowTransitions;

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

    /** @test */
    public function transition_to_on_a_new_model_instance_does_not_get_persisted_to_database()
    {
        $payment = new PaymentWithAllowTransitions();

        $payment->state->transitionTo(Canceled::class);

        $this->assertCount(0, Payment::all());
    }
}
