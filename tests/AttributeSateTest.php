<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\AttributeState\AttributeStateA;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AttributeStateB;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AttributeStateTransition;
use Spatie\ModelStates\Tests\Dummy\AttributeState\TestModelWithAttributeState;

class AttributeSateTest extends TestCase
{
    /** @test */
    public function test_default()
    {
        $model = new TestModelWithAttributeState();

        $this->assertTrue($model->state->equals(AttributeStateA::class));
    }

    /** @test */
    public function test_allowed_transition()
    {
        $model = new TestModelWithAttributeState();

        $model->state->transitionTo(AttributeStateB::class);

        $this->assertTrue($model->state->equals(AttributeStateB::class));
        $this->assertTrue(AttributeStateTransition::$transitioned);
    }
}
