<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use Spatie\ModelStates\Tests\Dummy\Dependency;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Pending;
use Spatie\ModelStates\Tests\Dummy\Transitions\ToFailed;
use Spatie\ModelStates\Tests\Dummy\Transitions\CreatedToPending;
use Spatie\ModelStates\Tests\Dummy\Transitions\PendingToPaid;
use Spatie\ModelStates\Tests\Dummy\Transitions\TransitionWithDependency;

class TransitionTest extends TestCase
{
    /** @test */
    public function transitions_can_be_performed()
    {
        $payment = Payment::create();

        $payment->state->transition(CreatedToPending::class);

        $this->assertInstanceOf(Pending::class, $payment->state);
    }

    /** @test */
    public function transitions_can_be_performed_with_extra_parameters()
    {
        $payment = Payment::create();

        $payment->state->transition(ToFailed::class, 'error message');

        $this->assertEquals('error message', $payment->error_message);
        $this->assertTrue($payment->state->is(Failed::class));
    }

    /** @test */
    public function transitions_objects_can_also_be_performed()
    {
        $payment = Payment::create();

        $payment->state->transition(new ToFailed($payment, 'error message'));

        $this->assertEquals('error message', $payment->error_message);
        $this->assertTrue($payment->state->is(Failed::class));
    }

    /** @test */
    public function transitions_with_dependencies_in_handle()
    {
        $payment = Payment::create();

        $payment->state->transition(TransitionWithDependency::class);

        $this->assertInstanceOf(Dependency::class, $payment->dependency);
    }

    /** @test */
    public function invalid_transitions_cannot_be_performed()
    {
        $payment = Payment::create();

        $this->expectException(CouldNotPerformTransition::class);

        $payment->state->transition(PendingToPaid::class);
    }

    /** @test */
    public function multiple_from_transitions_can_be_configured_at_once()
    {
        $payment = Payment::create([
            'state' => Pending::class,
        ]);

        $payment->state->transitionTo(Failed::class, 'message');

        $this->assertTrue($payment->state->is(Failed::class));

        $payment = Payment::create([
            'state' => Created::class,
        ]);

        $payment->state->transitionTo(Failed::class, 'message');

        $this->assertTrue($payment->state->is(Failed::class));
    }
}
