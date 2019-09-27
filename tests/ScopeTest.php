<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\Tests\Dummy\States\Created;

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
}
