<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\States\StateB;
use Spatie\ModelStates\Tests\Dummy\States\StateC;
use Spatie\ModelStates\Tests\Dummy\TestModelWithDefault;

class StateTest extends TestCase
{
    /** @test */
    public function transitionable_states()
    {
        $modelA = TestModelWithDefault::create();

        $this->assertEquals([
            StateB::getMorphClass(),
            StateC::getMorphClass(),
        ], $modelA->state->transitionableStates());

        $modelB = TestModelWithDefault::create([
            'state' => StateC::class,
        ]);

        $this->assertEquals([], $modelB->state->transitionableStates());
    }

    /** @test */
    public function test_equals()
    {
        $modelA = TestModelWithDefault::create();

        $modelB = TestModelWithDefault::create();

        $this->assertTrue($modelA->state->equals($modelB->state));

        $modelA = TestModelWithDefault::create();

        $modelB = TestModelWithDefault::create([
            'state' => StateC::class,
        ]);

        $this->assertFalse($modelA->state->equals($modelB->state));
    }
}
