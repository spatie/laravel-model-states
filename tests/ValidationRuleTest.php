<?php

namespace Spatie\ModelStates\Tests;

use Illuminate\Support\Facades\Validator;
use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Validation\ValidStateRule;

class ValidationRuleTest extends TestCase
{
    /** @test */
    public function test_validation()
    {
        $rule = new ValidStateRule(ModelState::class);

        $this->assertTrue(! Validator::make(
            ['state' => StateA::getMorphClass()],
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
        $rule = (new ValidStateRule(ModelState::class))->required();

        $this->assertTrue(Validator::make(
            ['state' => null],
            ['state' => $rule]
        )->fails());

        $rule = (new ValidStateRule(ModelState::class))->nullable();

        $this->assertFalse(Validator::make(
            ['state' => null],
            ['state' => $rule]
        )->fails());
    }
}
