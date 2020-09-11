<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Exceptions\TransitionNotAllowed;
use Spatie\ModelStates\Exceptions\TransitionNotFound;
use Spatie\ModelStates\Tests\Dummy\States\StateA;
use Spatie\ModelStates\Tests\Dummy\States\StateB;
use Spatie\ModelStates\Tests\Dummy\States\StateC;
use Spatie\ModelStates\Tests\Dummy\States\StateD;
use Spatie\ModelStates\Tests\Dummy\TestModel;
use Spatie\ModelStates\Tests\Dummy\TestModelWithCustomTransition;
use Spatie\ModelStates\Tests\Dummy\TestModelWithMultipleFromTransitions;
use Spatie\ModelStates\Tests\Dummy\TestModelWithTransitionsFromArray;
use Spatie\ModelStates\Tests\Dummy\Transitions\CustomInvalidTransition;
use Spatie\ModelStates\Tests\Dummy\Transitions\CustomTransition;

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

    /** @test */
    public function custom_transition_test()
    {
        $model = TestModelWithCustomTransition::create([
            'state' => StateA::class,
        ]);

        $message = 'my message';

        $model->state->transitionTo(StateB::class, $message);

        $model->refresh();

        $this->assertInstanceOf(StateB::class, $model->state);
        $this->assertEquals($message, $model->message);
    }

    /** @test */
    public function directly_transition()
    {
        $model = TestModelWithCustomTransition::create([
            'state' => StateA::class,
        ]);

        $message = 'my message';

        $model->state->transition(new CustomTransition($model, $message));

        $model->refresh();

        $this->assertInstanceOf(StateB::class, $model->state);
        $this->assertEquals($message, $model->message);
    }

    /** @test */
    public function test_cannot_transition()
    {
        $model = TestModelWithCustomTransition::create([
            'state' => StateA::class,
        ]);

        $this->expectException(TransitionNotAllowed::class);

        $model->state->transition(new CustomInvalidTransition($model));
    }
}
