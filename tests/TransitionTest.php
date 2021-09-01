<?php

namespace Spatie\ModelStates\Tests;

use Illuminate\Support\Facades\Event;
use Spatie\ModelStates\DefaultTransition;
use Spatie\ModelStates\Events\StateChanged;
use Spatie\ModelStates\Exceptions\TransitionNotAllowed;
use Spatie\ModelStates\Exceptions\TransitionNotFound;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateC;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateD;
use Spatie\ModelStates\Tests\Dummy\OtherModelStates\StateX;
use Spatie\ModelStates\Tests\Dummy\OtherModelStates\StateY;
use Spatie\ModelStates\Tests\Dummy\OtherModelStates\StateZ;
use Spatie\ModelStates\Tests\Dummy\TestModel;
use Spatie\ModelStates\Tests\Dummy\TestModelWithCustomTransition;
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
        $modelA = TestModel::create([
            'state' => StateA::class,
        ]);

        $modelA->state->transitionTo(StateC::getMorphClass());

        $modelA->refresh();

        $this->assertInstanceOf(StateC::class, $modelA->state);

        $modelB = TestModel::create([
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
            'state' => StateX::class,
        ]);

        $message = 'my message';

        $model->state->transitionTo(StateY::class, $message);

        $model->refresh();

        $this->assertInstanceOf(StateY::class, $model->state);
        $this->assertEquals($message, $model->message);
    }

    /** @test */
    public function directly_transition()
    {
        $model = TestModelWithCustomTransition::create([
            'state' => StateX::class,
        ]);

        $message = 'my message';

        $model->state->transition(new CustomTransition($model, $message));

        $model->refresh();

        $this->assertInstanceOf(StateY::class, $model->state);
        $this->assertEquals($message, $model->message);
    }

    /** @test */
    public function test_cannot_transition()
    {
        $model = TestModelWithCustomTransition::create([
            'state' => StateX::class,
        ]);

        $this->expectException(TransitionNotAllowed::class);

        $model->state->transition(new CustomInvalidTransition($model));
    }

    /** @test */
    public function test_custom_transition_blocks_can_transition_to()
    {
        $model = TestModelWithCustomTransition::create([
            'state' => StateX::class,
        ]);

        $this->assertFalse($model->state->canTransitionTo(StateZ::class));
    }

    /** @test */
    public function test_custom_transition_doesnt_block_can_transition_to()
    {
        $model = TestModelWithCustomTransition::create([
            'state' => StateX::class,
        ]);

        $this->assertTrue($model->state->canTransitionTo(StateY::class));
    }

    /** @test */
    public function event_is_triggered_after_transition()
    {
        Event::fake();

        $model = TestModel::create([
            'state' => StateA::class,
        ]);

        $model->state->transitionTo(StateB::class);

        Event::assertDispatched(StateChanged::class, function (StateChanged $event) use ($model) {
            return $event->transition instanceof DefaultTransition
                && $event->initialState instanceof StateA
                && $event->finalState instanceof StateB
                && $event->model->is($model);
        });
    }

    /** @test */
    public function can_transition_twice()
    {
        $model = TestModel::create([
            'state' => StateA::class,
        ]);

        $model->state->transitionTo(StateB::class);
        $model->state->transitionTo(StateC::class);

        $model->refresh();

        $this->assertInstanceOf(StateC::class, $model->state);
    }
}
