<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\Tests\Dummy\DummyState;
use Spatie\ModelStates\Tests\Dummy\ModelWithMultipleStates;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\PaymentWithAllowTransitions;
use Spatie\ModelStates\Tests\Dummy\PaymentWithMultipleStates;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
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
    public function state_can_transition_to_class()
    {
        $payment = Payment::create([
            'state' => Created::class,
        ]);

        $this->assertTrue($payment->canTransitionTo(Pending::class));
        $this->assertFalse($payment->canTransitionTo(Paid::class));
    }

    /** @test */
    public function state_can_transition_to_name()
    {
        $payment = Payment::create([
            'state' => Created::class,
        ]);

        $this->assertTrue($payment->canTransitionTo('pending'));
        $this->assertFalse($payment->canTransitionTo('paid'));
    }

    /** @test */
    public function state_can_transition_to_state_instance()
    {
        $payment = Payment::create([
            'state' => Created::class,
        ]);

        $this->assertTrue($payment->canTransitionTo(new Pending($payment)));
        $this->assertFalse($payment->canTransitionTo(new Paid($payment)));
    }

    /** @test */
    public function transition_to_with_multiple_states_throws_exception_on_undefined_class_field()
    {
        $this->expectException(InvalidConfig::class);

        $payment = PaymentWithMultipleStates::create();

        $payment->canTransitionTo(Pending::class);
    }

    /** @test */
    public function transition_to_with_multiple_states_throws_exception_on_undefined_name_field()
    {
        $this->expectException(InvalidConfig::class);

        $payment = PaymentWithMultipleStates::create();

        $payment->canTransitionTo('pending');
    }

    /** @test */
    public function transition_to_with_multiple_states_throws_exception_on_undefined_instance_field()
    {
        $this->expectException(InvalidConfig::class);

        $payment = PaymentWithMultipleStates::create();

        $payment->canTransitionTo(new Pending($payment));
    }

    /** @test */
    public function explicitly_defined_state_can_transition_to_class()
    {
        $payment = PaymentWithMultipleStates::create([
            'stateA' => Created::class,
        ]);

        $this->assertTrue($payment->canTransitionTo(Pending::class, 'stateA'));
        $this->assertFalse($payment->canTransitionTo(Paid::class, 'stateA'));
    }

    /** @test */
    public function explicitly_defined_state_can_transition_to_name()
    {
        $payment = PaymentWithMultipleStates::create([
            'stateA' => Created::class,
        ]);

        $this->assertTrue($payment->canTransitionTo('pending', 'stateA'));
        $this->assertFalse($payment->canTransitionTo('paid', 'stateA'));
    }

    /** @test */
    public function explicitly_defined_state_can_transition_to_state_instance()
    {
        $payment = PaymentWithMultipleStates::create([
            'stateA' => Created::class,
        ]);

        $this->assertTrue($payment->canTransitionTo(new Pending($payment), 'stateA'));
        $this->assertFalse($payment->canTransitionTo(new Paid($payment), 'stateA'));
    }
}
