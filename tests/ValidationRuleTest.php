<?php

namespace Spatie\ModelStates\Tests;

use Illuminate\Support\Facades\Validator;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Validation\ValidStateRule;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\PaymentState;

class ValidationRuleTest extends TestCase
{
    /** @test */
    public function test_validation()
    {
        $rule = new ValidStateRule(PaymentState::class);

        $this->assertTrue(! Validator::make(
            ['state' => 'created'],
            ['state' => $rule]
        )->fails());

        $this->assertTrue(! Validator::make(
            ['state' => Created::class],
            ['state' => $rule]
        )->fails());

        $this->assertTrue(! Validator::make(
            ['state' => new Created(new Payment())],
            ['state' => $rule]
        )->fails());

        $this->assertFalse(! Validator::make(
            ['state' => 'wrong'],
            ['state' => $rule]
        )->fails());
    }

    /** @test */
    public function nullable_validation()
    {
        $rule = (new ValidStateRule(PaymentState::class))->nullable();

        $this->assertTrue(! Validator::make(
            ['state' => null],
            ['state' => $rule]
        )->fails());

        $rule = (new ValidStateRule(PaymentState::class))->required();

        $this->assertFalse(! Validator::make(
            ['state' => null],
            ['state' => $rule]
        )->fails());

        $rule = (new ValidStateRule(PaymentState::class));

        $this->assertFalse(! Validator::make(
            ['state' => null],
            ['state' => $rule]
        )->fails());
    }
}
