<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\Tests\Dummy\States\Paid;
use Spatie\ModelStates\Tests\Dummy\States\PaymentState;

/**
 * @method static self first()
 * @method static self find(int $id)
 * @method static self create(array $data = [])
 * @property int $id
 * @property string $cancelled_at
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
class PaymentWithDefaultStatePaid extends Model
{
    use HasStates;

    protected $table = 'payments';

    protected $guarded = [];

    protected function registerStates(): void
    {
        $this->addState('state', PaymentState::class)->default(Paid::class);
    }
}
