<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Exceptions\TransitionNotFound;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Tests\Dummy\States\StateA;
use Spatie\ModelStates\Tests\Dummy\States\StateB;
use Spatie\ModelStates\Tests\Dummy\States\StateC;
use Spatie\ModelStates\Tests\Dummy\States\StateD;
use Spatie\ModelStates\Tests\Dummy\TestModel;
use Spatie\ModelStates\Tests\Dummy\TestModelWithMultipleFromTransitions;
use Spatie\ModelStates\Tests\Dummy\TestModelWithTransitionsFromArray;

class TransitionTest extends TestCase
{
    /** @test */
    public function allowed_transition()
    {
        $model = TestModel::create([
            'state' => StateA::class,
        ]);

        $model->state->transitionTo(StateB::class);

        $model->refresh();

        $this->assertInstanceOf(StateB::class, $model->state);
    }

    /** @test */
    public function allowed_transition_with_morph_mame()
    {
        $model = TestModel::create([
            'state' => StateA::class,
        ]);

        $model->state->transitionTo(StateD::getMorphClass());

        $model->refresh();

        $this->assertInstanceOf(StateD::class, $model->state);
    }

    /** @test */
    public function allowed_transition_configured_with_multiple_from()
    {
        $modelA = TestModelWithMultipleFromTransitions::create([
            'state' => StateA::class,
        ]);

        $modelA->state->transitionTo(StateC::getMorphClass());

        $modelA->refresh();

        $this->assertInstanceOf(StateC::class, $modelA->state);

        $modelB = TestModelWithMultipleFromTransitions::create([
            'state' => StateB::class,
        ]);

        $modelB->state->transitionTo(StateC::getMorphClass());

        $modelB->refresh();

        $this->assertInstanceOf(StateC::class, $modelB->state);
    }

    /** @test */
    public function allowed_transition_configured_from_array()
    {
        $model = TestModelWithTransitionsFromArray::create([
            'state' => StateA::class,
        ]);

        $model->state->transitionTo(StateC::class);

        $model->refresh();

        $this->assertInstanceOf(StateC::class, $model->state);
    }

    /** @test */
    public function disallowed_transition()
    {
        $model = TestModel::create([
            'state' => StateB::class,
        ]);

        $this->expectException(TransitionNotFound::class);

        $model->state->transitionTo(StateA::class);
    }
}
