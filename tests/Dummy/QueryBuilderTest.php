<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\ModelStates\StateA;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateB;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateC;
use Spatie\ModelStates\Tests\Dummy\ModelStates\StateD;
use Spatie\ModelStates\Tests\TestCase;

class QueryBuilderTest extends TestCase
{
    /** @test */
    public function test_where_state()
    {
        $model = TestModel::create([
            'state' => StateC::class,
        ]);

        expect(1)->toEqual(
            TestModel::query()
                ->whereState('state', StateC::class)
                ->where('id', $model->id)
                ->count()
        );

        expect(1)->toEqual(
            TestModel::query()
                ->whereState('state', StateC::getMorphClass())
                ->where('id', $model->id)
                ->count()
        );

        expect(1)->toEqual(
            TestModel::query()
                ->whereState('state', [StateA::class, StateC::class])
                ->where('id', $model->id)
                ->count()
        );

        expect(0)->toEqual(
            TestModel::query()
                ->whereState('state', StateA::class)
                ->where('id', $model->id)
                ->count()
        );

        expect(0)->toEqual(
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

        expect(0)->toEqual(
            TestModel::query()
                ->whereNotState('state', StateC::class)
                ->where('id', $model->id)
                ->count()
        );

        expect(0)->toEqual(
            TestModel::query()
                ->whereNotState('state', StateC::getMorphClass())
                ->where('id', $model->id)
                ->count()
        );

        expect(0)->toEqual(
            TestModel::query()
                ->whereNotState('state', [StateA::class, StateC::class])
                ->where('id', $model->id)
                ->count()
        );

        expect(1)->toEqual(
            TestModel::query()
                ->whereNotState('state', StateA::class)
                ->where('id', $model->id)
                ->count()
        );

        expect(1)->toEqual(
            TestModel::query()
                ->whereNotState('state', [StateA::class, StateB::class])
                ->where('id', $model->id)
                ->count()
        );
    }

    /** @test */
    public function test_or_where_state()
    {
        $modelOne = TestModel::create([ 'state' => StateB::class ]);
        $modelTwo = TestModel::create([ 'state' => StateC::class ]);

        expect(0)->toEqual(
            TestModel::query()
                ->whereState('state', StateA::class)
                ->orWhereState('state', StateC::class)
                ->where('id', $modelOne->id)
                ->count()
        );

        expect(1)->toEqual(
            TestModel::query()
                ->whereState('state', StateA::class)
                ->orWhereState('state', StateC::class)
                ->where('id', $modelTwo->id)
                ->count()
        );

        expect(2)->toEqual(
            TestModel::query()
                ->whereState('state', StateB::class)
                ->orWhereState('state', StateC::class)
                ->count()
        );
    }

    /** @test */
    public function test_or_where_not_state()
    {
        $modelOne = TestModel::create([ 'state' => StateB::class ]);
        $modelTwo = TestModel::create([ 'state' => StateC::class ]);

        expect(1)->toEqual(
            TestModel::query()
                ->whereState('state', StateA::class)
                ->orWhereNotState('state', StateC::class)
                ->where('id', $modelOne->id)
                ->count()
        );

        expect(0)->toEqual(
            TestModel::query()
                ->whereState('state', StateA::class)
                ->orWhereNotState('state', StateC::class)
                ->where('id', $modelTwo->id)
                ->count()
        );

        expect(2)->toEqual(
            TestModel::query()
                ->whereNotState('state', StateD::class)
                ->orWhereNotState('state', StateA::class)
                ->count()
        );
    }
}
