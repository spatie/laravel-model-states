<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Tests\Dummy\States\PaymentState;
use Spatie\ModelStates\Tests\Dummy\States\Pending;
use Spatie\ModelStates\Tests\Dummy\Transitions\CreatedToPending;
use Spatie\ModelStates\Tests\Dummy\Transitions\ToFailed;

/**
 * @method static \Spatie\ModelStates\Tests\Dummy\Payment first()
 * @method static \Spatie\ModelStates\Tests\Dummy\Payment find(int $id)
 * @method static \Spatie\ModelStates\Tests\Dummy\Payment create(array $data = [])
 * @property int $id
 * @property string $failed_at
 * @property string $paid_at
 * @property string $error_message
 *
 * @property \Spatie\ModelStates\Tests\Dummy\States\PaymentState $state
 *
 * @method static self whereState(string $field, $state)
 * @method static self whereNotState(string $field, $state)
 * @method int count()
 */
class PaymentWithMultipleStates extends Model
{
    use HasStates;

    protected $table = 'payments_with_multiple_states';

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->stateA = $this->stateA ?? new Created($this);
        $this->stateB = $this->stateB ?? new Created($this);
    }

    protected function registerStates(): void
    {
        $this->addState('stateA', PaymentState::class)
            ->allowTransition(Created::class, Pending::class, CreatedToPending::class)
            ->allowTransition([Created::class, Pending::class], Failed::class, ToFailed::class)
            ->allowTransition(Pending::class, Paid::class);

        $this->addState('stateB', PaymentState::class)
            ->allowTransition(Created::class, Pending::class, CreatedToPending::class)
            ->allowTransition([Created::class, Pending::class], Failed::class, ToFailed::class)
            ->allowTransition(Pending::class, Paid::class);
    }
}
