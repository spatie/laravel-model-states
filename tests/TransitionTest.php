<?php

namespace Spatie\State\Tests;

use Spatie\State\Exceptions\TransitionError;
use Spatie\State\Tests\Dummy\Dependency;
use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Failed;
use Spatie\State\Tests\Dummy\States\Pending;
use Spatie\State\Tests\Dummy\Transitions\CreatedToFailed;
use Spatie\State\Tests\Dummy\Transitions\CreatedToPending;
use Spatie\State\Tests\Dummy\Transitions\PendingToPaid;
use Spatie\State\Tests\Dummy\Transitions\TransitionWithDependency;

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

        $payment->state->transition(CreatedToFailed::class, 'error message');

        $this->assertEquals('error message', $payment->error_message);
        $this->assertTrue($payment->state->is(Failed::class));
    }

    /** @test */
    public function transitions_objects_can_also_be_performed()
    {
        $payment = Payment::create();

        $payment->state->transition(new CreatedToFailed($payment, 'error message'));

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

        $this->expectException(TransitionError::class);

        $payment->state->transition(PendingToPaid::class);
    }
}
