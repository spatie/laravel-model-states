<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Exceptions\TransitionNotFound;
use Spatie\ModelStates\Tests\Dummy\States\StateA;
use Spatie\ModelStates\Tests\Dummy\States\StateB;
use Spatie\ModelStates\Tests\Dummy\TestModel;

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
    public function disallowed_transition()
    {
        $model = TestModel::create([
            'state' => StateB::class,
        ]);

        $this->expectException(TransitionNotFound::class);

        $model->state->transitionTo(StateA::class);
    }
}
