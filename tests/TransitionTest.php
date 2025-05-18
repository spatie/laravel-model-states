<?php

use Spatie\ModelStates\Tests\Dummy\IgnoreSameStateModelState\IgnoreSameStateModelAttributeStateA;
use Illuminate\Support\Facades\Event;
use Spatie\ModelStates\DefaultTransition;
use Spatie\ModelStates\Events\StateChanged;
use Spatie\ModelStates\Exceptions\TransitionNotAllowed;
use Spatie\ModelStates\Exceptions\TransitionNotFound;
use Spatie\ModelStates\Tests\Dummy\IgnoreSameStateModelState\IgnoreSameStateModelStateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateC;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateD;
use Spatie\ModelStates\Tests\Dummy\OtherModelStates\StateX;
use Spatie\ModelStates\Tests\Dummy\OtherModelStates\StateY;
use Spatie\ModelStates\Tests\Dummy\OtherModelStates\StateZ;
use Spatie\ModelStates\Tests\Dummy\TestModel;
use Spatie\ModelStates\Tests\Dummy\TestModelIgnoresSameState;
use Spatie\ModelStates\Tests\Dummy\TestModelIgnoresSameStateByAttribute;
use Spatie\ModelStates\Tests\Dummy\TestModelUpdatingEvent;
use Spatie\ModelStates\Tests\Dummy\TestModelWithCustomTransition;
use Spatie\ModelStates\Tests\Dummy\TestModelWithTransitionsFromArray;
use Spatie\ModelStates\Tests\Dummy\Transitions\CustomInvalidTransition;
use Spatie\ModelStates\Tests\Dummy\Transitions\CustomTransition;

it('allowed transition', function () {
    $model = TestModel::create([
        'state' => StateA::class,
    ]);

    $model->state->transitionTo(StateB::class);

    $model->refresh();

    expect($model->state)->toBeInstanceOf(StateB::class);
});

it('allowed transition with morph mame', function () {
    $model = TestModel::create([
        'state' => StateA::class,
    ]);

    $model->state->transitionTo(StateD::getMorphClass());

    $model->refresh();

    expect($model->state)->toBeInstanceOf(StateD::class);
});

it('allowed transition configured with multiple from', function () {
    $modelA = TestModel::create([
        'state' => StateA::class,
    ]);

    $modelA->state->transitionTo(StateC::getMorphClass());

    $modelA->refresh();

    expect($modelA->state)->toBeInstanceOf(StateC::class);

    $modelB = TestModel::create([
        'state' => StateB::class,
    ]);

    $modelB->state->transitionTo(StateC::getMorphClass());

    $modelB->refresh();

    expect($modelB->state)->toBeInstanceOf(StateC::class);
});

it('allowed transition configured from array', function () {
    $model = TestModelWithTransitionsFromArray::create([
        'state' => StateA::class,
    ]);

    $model->state->transitionTo(StateC::class);

    $model->refresh();

    expect($model->state)->toBeInstanceOf(StateC::class);
});

it('disallowed transition', function () {
    $model = TestModel::create([
        'state' => StateB::class,
    ]);

    $this->expectException(TransitionNotFound::class);

    $model->state->transitionTo(StateA::class);
});

it('fails transition to same state when not allowed', function () {
    $model = TestModel::create([
        'state' => StateA::class,
    ]);

    $this->expectException(TransitionNotFound::class);

    $model->state->transitionTo(StateA::class);
});

it('custom transition test', function () {
    $model = TestModelWithCustomTransition::create([
        'state' => StateX::class,
    ]);

    $message = 'my message';

    $model->state->transitionTo(StateY::class, $message);

    $model->refresh();

    expect($model->state)->toBeInstanceOf(StateY::class);
    expect($model->message)->toEqual($message);
});

it('custom transition inherited from default test', function () {
    Event::fake();
    $model = TestModelWithCustomTransition::create([
        'state' => StateY::class,
    ]);

    $model->state->transitionTo(StateZ::class);

    $model->refresh();

    expect($model->state)->toBeInstanceOf(StateZ::class);
    Event::assertNotDispatched(TestModelUpdatingEvent::class);
});

it('directly transition', function () {
    $model = TestModelWithCustomTransition::create([
        'state' => StateX::class,
    ]);

    $message = 'my message';

    $model->state->transition(new CustomTransition($model, $message));

    $model->refresh();

    expect($model->state)->toBeInstanceOf(StateY::class);
    expect($model->message)->toEqual($message);
});

it('test cannot transition', function () {
    $model = TestModelWithCustomTransition::create([
        'state' => StateX::class,
    ]);

    $this->expectException(TransitionNotAllowed::class);

    $model->state->transition(new CustomInvalidTransition($model));
});

it('test custom transition blocks can transition to', function () {
    $model = TestModelWithCustomTransition::create([
        'state' => StateX::class,
    ]);

    expect($model->state->canTransitionTo(StateZ::class))->toBeFalse();
});

it('test custom transition doesnt block can transition to', function () {
    $model = TestModelWithCustomTransition::create([
        'state' => StateX::class,
    ]);

    expect($model->state->canTransitionTo(StateY::class))->toBeTrue();
});

it('event is triggered after transition', function () {
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
});

it('can transition twice', function () {
    $model = TestModel::create([
        'state' => StateA::class,
    ]);

    $model->state->transitionTo(StateB::class);
    $model->state->transitionTo(StateC::class);

    $model->refresh();

    expect($model->state)->toBeInstanceOf(StateC::class);
});

it('ignore transition to same state', function () {
    $model = TestModelIgnoresSameState::create([
        'state' => IgnoreSameStateModelStateA::class
    ]);

    expect($model->state->canTransitionTo(IgnoreSameStateModelStateA::class))->toBeTrue();

    $model->state->transitionTo(IgnoreSameStateModelStateA::class);

    expect($model->state)->toBeInstanceOf(IgnoreSameStateModelStateA::class);
});

it('ignore transition to same state using Attribute', function () {
    $model = TestModelIgnoresSameStateByAttribute::create([
        'state' => IgnoreSameStateModelAttributeStateA::class
    ]);

    expect($model->state->canTransitionTo(IgnoreSameStateModelAttributeStateA::class))->toBeTrue();

    $model->state->transitionTo(IgnoreSameStateModelAttributeStateA::class);

    expect($model->state)->toBeInstanceOf(IgnoreSameStateModelAttributeStateA::class);
});
