<?php

namespace Spatie\State\Tests\Validation;

use Illuminate\Support\Facades\Validator;
use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Created;
use Spatie\State\Tests\Dummy\States\PaymentState;
use Spatie\State\Tests\TestCase;
use Spatie\State\Validation\ValidStateRule;

class ValidStateRuleTest extends TestCase
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
