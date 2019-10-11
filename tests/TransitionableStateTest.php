<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Pending;
use Spatie\ModelStates\Tests\Dummy\ModelWithMultipleStates;

class TransitionableStateTest extends TestCase
{
    /** @test */
    public function transitionable_states_with_field()
    {
        $payment = new Payment();

        $transitionableStates = $payment->transitionableStates(Created::class, 'state');

        $this->assertEquals(
            $transitionableStates,
            [Pending::getMorphClass(), Failed::getMorphClass()]
        );
    }

    /** @test */
    public function transitionable_states_without_field()
    {
        $payment = new Payment();

        $transitionableStates = $payment->transitionableStates(Created::class);

        $this->assertEquals(
            $transitionableStates,
            [Pending::getMorphClass(), Failed::getMorphClass()]
        );
    }

    /** @test */
    public function transitionable_states_with_invalid_field_fails()
    {
        $this->expectException(InvalidConfig::class);

        $payment = new Payment();

        $transitionableStates = $payment->transitionableStates(Created::class, 'wrong');
    }

    /** @test */
    public function transitionable_states_with_multiple_states_without_field_faild()
    {
        $this->expectException(InvalidConfig::class);

        $payment = new ModelWithMultipleStates();

        $transitionableStates = $payment->transitionableStates(DummyState::class);
    }
}
