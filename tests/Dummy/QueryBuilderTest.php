<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateC;
use Spatie\ModelStates\Tests\TestCase;

class QueryBuilderTest extends TestCase
{
    /** @test */
    public function test_where_state()
    {
        $model = TestModel::create([
            'state' => StateC::class,
        ]);

        $this->assertEquals(
            1,
            TestModel::query()
                ->whereState('state', StateC::class)
                ->where('id', $model->id)
                ->count()
        );

        $this->assertEquals(
            1,
            TestModel::query()
                ->whereState('state', StateC::getMorphClass())
                ->where('id', $model->id)
                ->count()
        );

        $this->assertEquals(
            1,
            TestModel::query()
                ->whereState('state', [StateA::class, StateC::class])
                ->where('id', $model->id)
                ->count()
        );

        $this->assertEquals(
            0,
            TestModel::query()
                ->whereState('state', StateA::class)
                ->where('id', $model->id)
                ->count()
        );

        $this->assertEquals(
            0,
            TestModel::query()
                ->whereState('state', [StateA::class, StateB::class])
                ->where('id', $model->id)
                ->count()
        );
    }

    /** @test */
    public function test_where_not_state()
    {
        $model = TestModel::create([
            'state' => StateC::class,
        ]);

        $this->assertEquals(
            0,
            TestModel::query()
                ->whereNotState('state', StateC::class)
                ->where('id', $model->id)
                ->count()
        );

        $this->assertEquals(
            0,
            TestModel::query()
                ->whereNotState('state', StateC::getMorphClass())
                ->where('id', $model->id)
                ->count()
        );

        $this->assertEquals(
            0,
            TestModel::query()
                ->whereNotState('state', [StateA::class, StateC::class])
                ->where('id', $model->id)
                ->count()
        );

        $this->assertEquals(
            1,
            TestModel::query()
                ->whereNotState('state', StateA::class)
                ->where('id', $model->id)
                ->count()
        );

        $this->assertEquals(
            1,
            TestModel::query()
                ->whereNotState('state', [StateA::class, StateB::class])
                ->where('id', $model->id)
                ->count()
        );
    }
}
