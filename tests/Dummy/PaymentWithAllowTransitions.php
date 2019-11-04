<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Tests\Dummy\States\Failed;
use Spatie\ModelStates\Tests\Dummy\States\Created;
use Spatie\ModelStates\Tests\Dummy\States\Pending;
use Spatie\ModelStates\Tests\Dummy\States\Canceled;
use Spatie\ModelStates\Tests\Dummy\States\PaymentState;
use Spatie\ModelStates\Tests\Dummy\Transitions\ToFailed;
use Spatie\ModelStates\Tests\Dummy\Transitions\CreatedToPending;

/**
 * @method static self first
 * @method static self find(int $id)
 * @method static self create(array $data = [])
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
class PaymentWithAllowTransitions extends Model
{
    use HasStates;

    protected $table = 'payments';

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->state = $this->state ?? new Created($this);
    }

    protected function registerStates(): void
    {
        $this->addState('state', PaymentState::class)
            ->allowTransitions([
                [Created::class, Pending::class, CreatedToPending::class],
                [Created::class, Failed::class, ToFailed::class],
                [Created::class, Canceled::class],
                [Pending::class, Paid::class],
            ]);
    }
}
