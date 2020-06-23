<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\Tests\Dummy\IntStates\IntStateA;
use Spatie\ModelStates\Tests\Dummy\IntStates\IntStateB;
use Spatie\ModelStates\Tests\Dummy\ModelWithIntState;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Paid;

class ScopeTest extends TestCase
{
    /** @test */
    public function scope_where_state()
    {
        $createdPayment = Payment::create();

        $paidPayment = Payment::create(['state' => Paid::class]);

        $this->assertEquals(1, Payment::whereState('state', Paid::class)->count());
        $this->assertEquals(1, Payment::whereState('state', Created::class)->count());
        $this->assertEquals(2, Payment::whereState('state', [Created::class, Paid::class])->count());

        $this->assertTrue($paidPayment->is(Payment::whereState('state', Paid::class)->first()));
        $this->assertTrue($createdPayment->is(Payment::whereState('state', Created::class)->first()));
    }

    /** @test */
    public function scope_where_not_state()
    {
        $createdPayment = Payment::create();

        $paidPayment = Payment::create(['state' => Paid::class]);

        $this->assertEquals(1, Payment::whereNotState('state', Paid::class)->count());
        $this->assertEquals(1, Payment::whereNotState('state', Created::class)->count());
        $this->assertEquals(0, Payment::whereNotState('state', [Created::class, Paid::class])->count());

        $this->assertFalse($paidPayment->is(Payment::whereNotState('state', Paid::class)->first()));
        $this->assertFalse($createdPayment->is(Payment::whereNotState('state', Created::class)->first()));
    }

    /** @test */
    public function scope_where_state_with_invalid_field_throws_exception()
    {
        $this->expectException(InvalidConfig::class);

        Payment::whereState('abc', Paid::class);
    }

    /** @test */
    public function scope_where_state_column_name_differs_from_field_name()
    {
        $createdPayment = Payment::create();

        $paidPayment = Payment::create(['state' => Paid::class]);

        $this->assertEquals(1, Payment::whereState('payments.state', Paid::class)->count());
        $this->assertEquals(1, Payment::whereState('payments.state', Created::class)->count());
        $this->assertEquals(2, Payment::whereState('payments.state', [Created::class, Paid::class])->count());

        $this->assertTrue($paidPayment->is(Payment::whereState('payments.state', Paid::class)->first()));
        $this->assertTrue($createdPayment->is(Payment::whereState('payments.state', Created::class)->first()));
    }

    /** @test */
    public function scope_where_not_state_column_name_differs_from_field_name()
    {
        $createdPayment = Payment::create();

        $paidPayment = Payment::create(['state' => Paid::class]);

        $this->assertEquals(1, Payment::whereNotState('payments.state', Paid::class)->count());
        $this->assertEquals(1, Payment::whereNotState('payments.state', Created::class)->count());
        $this->assertEquals(0, Payment::whereNotState('payments.state', [Created::class, Paid::class])->count());

        $this->assertFalse($paidPayment->is(Payment::whereNotState('payments.state', Paid::class)->first()));
        $this->assertFalse($createdPayment->is(Payment::whereNotState('payments.state', Created::class)->first()));
    }

    /** @test */
    public function scope_with_states_saved_as_tiny_ints()
    {
        ModelWithIntState::migrate();

        $modelA = ModelWithIntState::create([
            'state' => IntStateA::class,
        ]);

        $modelB = ModelWithIntState::create([
            'state' => IntStateB::class,
        ]);

        $this->assertTrue(ModelWithIntState::whereState('state', IntStateA::class)->first()->is($modelA));
        $this->assertTrue(ModelWithIntState::whereNotState('state', IntStateA::class)->first()->is($modelB));

        $this->assertTrue(ModelWithIntState::whereState('state', IntStateB::class)->first()->is($modelB));
        $this->assertTrue(ModelWithIntState::whereNotState('state', IntStateB::class)->first()->is($modelA));
    }
}
