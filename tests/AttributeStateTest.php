<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\AttributeState\AnotherDirectory\AttributeStateC;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AnotherDirectory\AttributeStateD;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AnotherDirectory\AttributeStateE;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AttributeStateA;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AttributeStateB;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AttributeStateTransition;
use Spatie\ModelStates\Tests\Dummy\AttributeState\TestModelWithAttributeState;

class AttributeStateTest extends TestCase
{
    /** @test */
    public function test_default()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped('Not PHP 8');

            return;
        }

        $model = new TestModelWithAttributeState();

        $this->assertTrue($model->state->equals(AttributeStateA::class));
    }

    /** @test */
    public function test_allowed_transition()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped('Not PHP 8');

            return;
        }

        $model = new TestModelWithAttributeState();

        $model->state->transitionTo(AttributeStateB::class);

        $this->assertTrue($model->state->equals(AttributeStateB::class));
        $this->assertTrue(AttributeStateTransition::$transitioned);
    }
	
	/** @test */
	public function test_registered_states()
	{
		if (PHP_VERSION_ID < 80000) {
			$this->markTestSkipped('Not PHP 8');
			
			return;
		}
		
		$model = new TestModelWithAttributeState();
		
		$this->assertSame([AttributeStateC::class, AttributeStateD::class, AttributeStateE::class], AttributeStateA::config()->registeredStates);
		$this->assertSame([AttributeStateC::class, AttributeStateD::class, AttributeStateE::class], AttributeStateC::config()->registeredStates);
		
		$this->assertTrue($model->state->equals(AttributeStateA::class));
		
		$model->state->transitionTo(AttributeStateC::class);
		
		$this->assertTrue($model->state->equals(AttributeStateC::class));
	}
}
