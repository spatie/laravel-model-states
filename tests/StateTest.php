<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\States\ModelState;
use Spatie\ModelStates\Tests\Dummy\States\StateA;
use Spatie\ModelStates\Tests\Dummy\States\StateB;
use Spatie\ModelStates\Tests\Dummy\States\StateC;
use Spatie\ModelStates\Tests\Dummy\States\StateD;
use Spatie\ModelStates\Tests\Dummy\TestModelWithDefault;

class StateTest extends TestCase
{
    /** @test */
    public function test_resolve_state_class()
    {
        $this->assertEquals(StateA::class, ModelState::resolveStateClass(StateA::class));
        $this->assertEquals(StateC::class, ModelState::resolveStateClass(StateC::class));
        $this->assertEquals(StateC::class, ModelState::resolveStateClass(StateC::getMorphClass()));
        $this->assertEquals(StateC::class, ModelState::resolveStateClass(StateC::$name));
        $this->assertEquals(StateD::class, ModelState::resolveStateClass(StateD::class));
        $this->assertEquals(StateD::class, ModelState::resolveStateClass(StateD::getMorphClass()));
        $this->assertEquals(StateD::class, ModelState::resolveStateClass(StateD::$name));
    }

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

        $this->assertTrue($modelA->state->equals(StateA::class));
    }

    /** @test */
    public function test_transition_to()
    {
        $modelA = TestModelWithDefault::create();

        $this->assertTrue($modelA->state->canTransitionTo(StateB::class));
        $this->assertTrue($modelA->state->canTransitionTo(StateC::class));

        $modelB = TestModelWithDefault::create([
            'state' => StateB::class,
        ]);

        $this->assertFalse($modelB->state->canTransitionTo(StateB::class));
        $this->assertFalse($modelB->state->canTransitionTo(StateC::class));
    }

    /** @test */
    public function test_get_states()
    {
        $states = TestModelWithDefault::getStates();

        $this->assertEquals(
            [
                'state' => [
                    StateA::getMorphClass(),
                    StateB::getMorphClass(),
                    StateC::getMorphClass(),
                    StateD::getMorphClass(),
                ],
            ],
            $states->toArray()
        );
    }

    /** @test */
    public function test_get_states_for()
    {
        $states = TestModelWithDefault::getStatesFor('state');

        $this->assertEquals(
            [
                StateA::getMorphClass(),
                StateB::getMorphClass(),
                StateC::getMorphClass(),
                StateD::getMorphClass(),
            ],
            $states->toArray()
        );
    }

    /** @test */
    public function test_get_default_states()
    {
        $states = TestModelWithDefault::getDefaultStates();

        $this->assertEquals(
            [
                'state' => StateA::getMorphClass(),
            ],
            $states->toArray()
        );
    }

    /** @test */
    public function test_get_default_states_for()
    {
        $defaultState = TestModelWithDefault::getDefaultStateFor('state');

        $this->assertEquals(StateA::getMorphClass(), $defaultState);
    }
}
