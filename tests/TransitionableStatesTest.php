<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\Tests\Dummy\ModelWithMultipleStates;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Pending;

class TransitionableStatesTest extends TestCase
{
    /** @test */
    public function transitionable_states_with_fieldname()
    {
        $payment = new Payment();

        $transitionableStates = $payment->transitionableStates(Created::class, 'state');

        $this->assertEquals(
            $transitionableStates,
            [Pending::getMorphClass(), Failed::getMorphClass()]
        );
    }

    /** @test */
    public function transitionable_states_without_fieldname()
    {
        $payment = new Payment();

        $transitionableStates = $payment->transitionableStates(Created::class);

        $this->assertEquals(
            $transitionableStates,
            [Pending::getMorphClass(), Failed::getMorphClass()]
        );
    }

    /** @test */
    public function transitionable_states_with_invalid_fieldname_fails()
    {
        $this->expectException(InvalidConfig::class);

        $payment = new Payment();

        $transitionableStates = $payment->transitionableStates(Created::class, 'wrong');
    }

    /** @test */
    public function transitionable_states_with_multiple_states_without_fieldname_fails()
    {
        $this->expectException(InvalidConfig::class);

        $payment = new ModelWithMultipleStates();

        $transitionableStates = $payment->transitionableStates(DummyState::class);
    }
}
