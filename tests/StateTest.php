<?php

namespace Spatie\ModelStates\Tests;

use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\WrongState;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Pending;
use Spatie\ModelStates\Tests\Dummy\States\PaymentState;
use Spatie\ModelStates\Tests\Dummy\States\PaidWithoutName;
use Spatie\ModelStates\Tests\Dummy\AutoDetectStates\StateA;
use Spatie\ModelStates\Tests\Dummy\PaymentWithDefaultStatePaid;
use Spatie\ModelStates\Tests\Dummy\AutoDetectStates\AbstractState;

class StateTest extends TestCase
{
    public function state_with_name_is_saved_with_its_class_name()
    {
        $payment = Payment::create([
            'state' => PaidWithoutName::class,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'state' => PaidWithoutName::getMorphClass(),
        ]);

        $this->assertInstanceOf(PaidWithoutName::class, $payment->state);
    }

    /** @test */
    public function state_is_properly_serialized()
    {
        $payment = Payment::create();

        $this->assertInstanceOf(Created::class, $payment->state);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'state' => Created::getMorphClass(),
        ]);

        $payment->state = new Pending($payment);

        $payment->save();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'state' => Pending::getMorphClass(),
        ]);
    }

    /** @test */
    public function create_with_state()
    {
        $payment = Payment::create([
            'state' => Paid::class,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'state' => Paid::getMorphClass(),
        ]);

        $this->assertInstanceOf(Paid::class, $payment->state);
    }

    /** @test */
    public function only_states_of_the_correct_type_are_allowed_via_create()
    {
        $this->expectException(InvalidConfig::class);

        Payment::create([
            'state' => WrongState::class,
        ]);
    }

    /** @test */
    public function only_states_of_the_correct_type_are_allowed_via_setter()
    {
        $payment = Payment::create();

        $this->expectException(InvalidConfig::class);

        $payment->state = new WrongState($payment);

        $payment->save();
    }

    /** @test */
    public function state_from_concrete_class()
    {
        $payment = Payment::create();

        $payment->state = new Paid($payment);

        $payment->save();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'state' => Paid::getMorphClass(),
        ]);
    }

    /** @test */
    public function state_from_class_name()
    {
        $payment = Payment::create([
            'state' => Paid::class,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'state' => Paid::getMorphClass(),
        ]);
    }

    /** @test */
    public function state_with_morphed_class_name()
    {
        $payment = Payment::create([
            'state' => 'paid',
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'state' => 'paid',
        ]);
    }

    /** @test */
    public function state_with_morphed_class_name_from_class_name()
    {
        $payment = Payment::create([
            'state' => Paid::class,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'state' => 'paid',
        ]);
    }

    /** @test */
    public function state_with_morphed_class_name_from_concrete_class()
    {
        $payment = new Payment();

        $payment->state = new Paid($payment);

        $payment->save();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'state' => 'paid',
        ]);
    }

    /** @test */
    public function is_one_of()
    {
        $payment = new Payment();

        $this->assertTrue($payment->state->isOneOf(
            Created::class,
            Paid::class
        ));

        $this->assertTrue($payment->state->isOneOf(
            new Created($payment)
        ));

        $this->assertTrue($payment->state->isOneOf(
            'created'
        ));

        $this->assertFalse($payment->state->isOneOf(
            Paid::class
        ));
    }

    /** @test */
    public function is_one_of_with_array()
    {
        $payment = new Payment();

        $this->assertTrue($payment->state->isOneOf([
            Created::class,
            Paid::class,
        ]));
    }

    /** @test */
    public function equals()
    {
        $createdA = new Created(new Payment());
        $createdB = new Created(new Payment());
        $paid = new Paid(new Payment());

        $this->assertTrue($createdA->equals($createdB));
        $this->assertTrue($createdA->equals(Created::class));
        $this->assertTrue($createdA->equals('created'));

        $this->assertFalse($createdA->equals($paid));
    }

    /** @test */
    public function resolve_state_without_explicit_mapping()
    {
        $state = AbstractState::find('a', new Payment());

        $this->assertInstanceOf(StateA::class, $state);
    }

    /** @test */
    public function all()
    {
        $all = PaymentState::all();

        $this->assertNotNull($all->first(function (string $className) {
            return $className === Paid::class;
        }));
    }

    /** @test */
    public function default_state_via_create()
    {
        $payment = PaymentWithDefaultStatePaid::create();

        $this->assertTrue($payment->state->is(Paid::class));
    }

    /** @test */
    public function default_state_via_new()
    {
        $payment = new PaymentWithDefaultStatePaid();

        $this->assertTrue($payment->state->is(Paid::class));
    }

    /** @test */
    public function to_json_works_properly()
    {
        $payment = new Payment();

        $this->assertEquals(<<<'JSON'
            {"state":"created"}
            JSON,
            $payment->toJson()
        );
    }
}
