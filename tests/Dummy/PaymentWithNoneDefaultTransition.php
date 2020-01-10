<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Refunded;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Tests\Dummy\States\PaymentState;
use Spatie\ModelStates\Tests\Dummy\Transitions\TransitionWithTimestamp;

/**
 * @method static \Spatie\ModelStates\Tests\Dummy\Payment first
 * @method static \Spatie\ModelStates\Tests\Dummy\Payment find(int $id)
 * @method static \Spatie\ModelStates\Tests\Dummy\Payment create(array $data = [])
 * @property int id
 * @property \Carbon\Carbon failed_at
 * @property \Carbon\Carbon paid_at
 * @property \Carbon\Carbon pending_at
 * @property string error_message
 *
 * @property \Spatie\ModelStates\Tests\Dummy\States\PaymentState state
 *
 * @method static self whereState(string $field, $state)
 * @method static self whereNotState(string $field, $state)
 * @method int count
 */
class PaymentWithNoneDefaultTransition extends Model
{
    use HasStates;

    protected $table = 'payments';

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->state = $this->state ?? new Created($this);
    }

    public function getDefaultTransitionClass(): string
    {
        return TransitionWithTimestamp::class;
    }

    protected function registerStates(): void
    {
        $this->addState('state', PaymentState::class)
            ->allowTransition(Paid::class, Refunded::class)
            ->default(Paid::class);
    }
}
