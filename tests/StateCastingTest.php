<?php

use Illuminate\Support\Facades\DB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateC;
use Spatie\ModelStates\Tests\Dummy\ModelStates\AnotherDirectory\StateF;
use Spatie\ModelStates\Tests\Dummy\ModelStates\AnotherDirectory\StateG;
use Spatie\ModelStates\Tests\Dummy\AliasedModelStates\Pending;
use Spatie\ModelStates\Tests\Dummy\TestModel;
use Spatie\ModelStates\Tests\Dummy\TestModelWithAliasedDefaultCastsMethod;
use Spatie\ModelStates\Tests\Dummy\TestModelWithCastsMethod;

it('state without alias is serialized on create', function () {
    $model = TestModel::create([
        'state' => StateA::class,
    ]);

    expect($model->state)->toBeInstanceOf(StateA::class);

    $this->assertDatabaseHas($model->getTable(), [
        'state' => StateA::getMorphClass(),
    ]);
});

it('custom registered state without alias is serialized on create', function () {
    $model = TestModel::create([
        'state' => StateF::class,
    ]);

    expect($model->state)->toBeInstanceOf(StateF::class);

    $this->assertDatabaseHas($model->getTable(), [
        'state' => StateF::getMorphClass(),
    ]);
});

it('state with alias is serialized on create when using class name', function () {
    $model = TestModel::create([
        'state' => StateC::class,
    ]);

    expect($model->state)->toBeInstanceOf(StateC::class);

    $this->assertDatabaseHas($model->getTable(), [
        'state' => StateC::getMorphClass(),
    ]);
});

it('custom registered state with alias is serialized on create when using class name', function () {
    $model = TestModel::create([
        'state' => StateG::class,
    ]);

    expect($model->state)->toBeInstanceOf(StateG::class);

    $this->assertDatabaseHas($model->getTable(), [
        'state' => StateG::getMorphClass(),
    ]);
});

it('state with alias is serialized on create when using alias', function () {
    $model = TestModel::create([
        'state' => StateC::getMorphClass(),
    ]);

    expect($model->state)->toBeInstanceOf(StateC::class);

    $this->assertDatabaseHas($model->getTable(), [
        'state' => StateC::getMorphClass(),
    ]);
});

it('custom registered state with alias is serialized on create when using alias', function () {
    $model = TestModel::create([
        'state' => StateG::getMorphClass(),
    ]);

    expect($model->state)->toBeInstanceOf(StateG::class);

    $this->assertDatabaseHas($model->getTable(), [
        'state' => StateG::getMorphClass(),
    ]);
});

it('state is immediately unserialized on property set', function () {
    $model = new TestModel();

    $model->state = StateA::class;

    expect($model->state)->toBeInstanceOf(StateA::class);
});

it('state is immediately unserialized on model construction', function () {
    $model = new TestModel([
        'state' => StateA::class,
    ]);

    expect($model->state)->toBeInstanceOf(StateA::class);
});

it('state is unserialized on fetch', function () {
    DB::table((new TestModel())->getTable())->insert([
        'id' => 1,
        'state' => StateA::getMorphClass(),
    ]);

    $model = TestModel::find(1);

    expect($model->state)->toBeInstanceOf(StateA::class);
});

it('default state is set when none provided', function () {
    $model = (new class() extends TestModel {
        public function registerStates(): void
        {
        $this
            ->addState('state', ModelState::class)
            ->default(StateA::class);
        }
    })->create();

    expect($model->state)->toBeInstanceOf(StateA::class);

    $this->assertDatabaseHas($model->getTable(), [
        'state' => StateA::getMorphClass(),
    ]);
});

it('field is always populated when set', function () {
    $model = new TestModel();

    expect($model->state)->toBeInstanceOf(StateA::class);

    $model->state = new StateB($model);

    expect($model->state)->toBeInstanceOf(StateB::class);

    expect($model->state->getField())->toEqual('state');
});

it('serializes to a value when calling toArray', function() {
    $model = new TestModel();

    expect($model->toArray()['state'])->toBe(StateA::class);
});

it('respects jsonSerialize in state classes', function() {
    $model = new TestModel([
        'state' => StateB::class,
    ]);

    expect($model->toJson())->toBe('{"state":{"name":"StateB"}}');
});

it('resolves state defaults on a fresh unsaved model when cast is declared via casts() method', function () {
    // Regression test for PHP 8.5 trait binding order change: on PHP 8.5,
    // initializeHasStates() runs before initializeHasAttributes(), so $this->casts
    // may not yet contain values from the casts() method when setStateDefaults() fires.
    // getStateConfigs() must read both the $casts property and the casts() method directly.
    $model = new TestModelWithCastsMethod();

    expect($model->state)->toBeInstanceOf(StateA::class);
});

it('resolves state on a fresh unsaved model constructed with attributes when cast is declared via casts() method', function () {
    $model = new TestModelWithCastsMethod(['state' => StateB::class]);

    expect($model->state)->toBeInstanceOf(StateB::class);
});

it('resolves an aliased default state when cast is declared via casts() method', function () {
    // Regression test for spatie/laravel-model-states#307: under PHP 8.5 trait
    // init order, initializeHasStates() fires before initializeHasAttributes(),
    // so $this->casts doesn't yet contain casts() entries when setStateDefaults()
    // writes the default. setAttribute() skipped the StateCaster and stored the
    // raw FQN instead of the morph alias, which later blew up in StateCaster::get
    // with "Undefined array key" because the mapping is keyed by alias.
    $model = new TestModelWithAliasedDefaultCastsMethod();

    expect($model->state)->toBeInstanceOf(Pending::class);
    expect($model->getAttributes()['state'] ?? null)->toBe(Pending::getMorphClass());
});
