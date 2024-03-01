<?php

use Illuminate\Support\Facades\DB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateC;
use Spatie\ModelStates\Tests\Dummy\ModelStates\AnotherDirectory\StateF;
use Spatie\ModelStates\Tests\Dummy\ModelStates\AnotherDirectory\StateG;
use Spatie\ModelStates\Tests\Dummy\TestModel;

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

it('serializes to a value', function() {
    $model = new TestModel();

    expect($model->toArray()['state'])->toBe(StateA::class);
});
