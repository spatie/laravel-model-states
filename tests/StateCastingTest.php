<?php

namespace Spatie\ModelStates\Tests;

use Illuminate\Support\Facades\DB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateC;
use Spatie\ModelStates\Tests\Dummy\TestModel;

class StateCastingTest extends TestCase
{
    /** @test */
    public function state_without_alias_is_serialized_on_create()
    {
        $model = TestModel::create([
            'state' => StateA::class,
        ]);

        $this->assertInstanceOf(StateA::class, $model->state);

        $this->assertDatabaseHas($model->getTable(), [
            'state' => StateA::getMorphClass(),
        ]);
    }

    /** @test */
    public function state_with_alias_is_serialized_on_create_when_using_class_name()
    {
        $model = TestModel::create([
            'state' => StateC::class,
        ]);

        $this->assertInstanceOf(StateC::class, $model->state);

        $this->assertDatabaseHas($model->getTable(), [
            'state' => StateC::getMorphClass(),
        ]);
    }

    /** @test */
    public function state_with_alias_is_serialized_on_create_when_using_alias()
    {
        $model = TestModel::create([
            'state' => StateC::getMorphClass(),
        ]);

        $this->assertInstanceOf(StateC::class, $model->state);

        $this->assertDatabaseHas($model->getTable(), [
            'state' => StateC::getMorphClass(),
        ]);
    }

    /** @test */
    public function state_is_immediately_unserialized_on_property_set()
    {
        $model = new TestModel();

        $model->state = StateA::class;

        $this->assertInstanceOf(StateA::class, $model->state);
    }

    /** @test */
    public function state_is_immediately_unserialized_on_model_construction()
    {
        $model = new TestModel([
            'state' => StateA::class,
        ]);

        $this->assertInstanceOf(StateA::class, $model->state);
    }

    /** @test */
    public function state_is_unserialized_on_fetch()
    {
        DB::table((new TestModel())->getTable())->insert([
            'id' => 1,
            'state' => StateA::getMorphClass(),
        ]);

        $model = TestModel::find(1);

        $this->assertInstanceOf(StateA::class, $model->state);
    }

    /** @test */
    public function default_state_is_set_when_none_provided()
    {
        $model = (new class extends TestModel {
            public function registerStates(): void
            {
                $this
                    ->addState('state', ModelState::class)
                    ->default(StateA::class);
            }
        })->create();

        $this->assertInstanceOf(StateA::class, $model->state);

        $this->assertDatabaseHas($model->getTable(), [
            'state' => StateA::getMorphClass(),
        ]);
    }
}
