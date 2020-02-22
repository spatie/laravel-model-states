<?php

namespace Spatie\ModelStates\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Event;
use Spatie\ModelStates\Events\StateChanged;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Tests\Dummy\Payment;
use Spatie\ModelStates\Tests\Dummy\States\Pending;
use Spatie\ModelStates\Tests\Dummy\Transitions\PendingToPaid;

class StateChangedEventTest extends TestCase
{
    /** @test */
    public function state_changed_event_is_fired_after_transition_run()
    {
        Event::fake();

        $payment = new Payment();

        $payment->state = new Pending($payment);

        $original = $payment->state;

        $payment->state->transition(PendingToPaid::class);

        Event::assertDispatched(
            StateChanged::class,
            function (StateChanged $event) use ($original, $payment) {
                $this->assertEquals($original, $event->initialState);

                // @see https://github.com/spatie/laravel-model-states/issues/49
                $this->assertEquals($payment->state, $event->finalState);

                $this->assertEquals($payment, $event->model);
                $this->assertInstanceOf(PendingToPaid::class, $event->transition);

                return true;
            }
        );
    }

    /**
     * @test
     * @see https://github.com/spatie/laravel-model-states/issues/49
     */
    public function state_changed_with_other_state_field()
    {
        Event::fake();

        TestModel::migrate();

        /** @var TestModel $model */
        $model = TestModel::create();

        $model->status->transitionTo(StateB::class);

        Event::assertDispatched(
            StateChanged::class,
            function (StateChanged $event) {
                $this->assertNull($event->finalState);

                return true;
            }
        );
    }
}

/**
 * @property \Spatie\ModelStates\Tests\AbstractState status
 */
class TestModel extends Model
{
    protected $guarded = [];

    protected $table = 'test_model';

    use HasStates;

    // Another random field
    public $state = 'abc';

    public static function migrate(): void
    {
        app()->get('db')->connection()->getSchemaBuilder()->create('test_model', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    protected function registerStates(): void
    {
        $this
            ->addState('status', AbstractState::class)
            ->default(StateA::class)
            ->allowTransition(StateA::class, StateB::class);
    }
}

abstract class AbstractState extends State
{
}

class StateA extends AbstractState
{
    public static $name = 1;
}

class StateB extends AbstractState
{
    public static $name = 2;
}
