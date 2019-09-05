<?php

namespace Spatie\State\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\State\Tests\Dummy\Payment;
use Spatie\State\Tests\Dummy\States\Created;
use Spatie\State\Tests\Dummy\States\Paid;
use Spatie\State\Tests\Dummy\States\Pending;
use Spatie\State\Tests\Dummy\Transitions\CreatedToPending;

class StateTest extends TestCase
{
    /** @test */
    public function state_is_properly_serialized()
    {
        $payment = Payment::create();

        $this->assertInstanceOf(Created::class, $payment->state);
        $this->assertTrue(Created::class === $payment->attributesToArray()['state']);

        $payment->state = new Pending($payment);

        $payment->save();

        $this->assertInstanceOf(Pending::class, $payment->state);
    }

    /** @test */
    public function transitions_can_be_performed()
    {
        $payment = Payment::create();

        $createdToPending = new CreatedToPending();

        $payment = $createdToPending($payment);

        $payment->refresh();

        $this->assertInstanceOf(Pending::class, $payment->state);
    }

    /** @test */
    public function create_with_state()
    {
        $payment = Payment::create([
            'state' => Paid::class,
        ]);

        $payment = $payment->fresh();

        $this->assertInstanceOf(Paid::class, $payment->state);
    }

    /** @test */
    public function save_with_morph_map()
    {
        Relation::morphMap([
            'created' => Created::class,
        ]);

        $payment = Payment::create();

        $this->assertEquals('created', $payment->attributesToArray()['state']);
    }

    /** @test */
    public function load_with_morph_map()
    {
        $payment = Payment::create();

        $payment->state = new Paid($payment);

        $payment->save();

        $payment = Payment::find($payment->id);

        $this->assertInstanceOf(Paid::class, $payment->state);
    }
}
