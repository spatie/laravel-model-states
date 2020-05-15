<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\Tests\Dummy\AutoDetectStates\StateA;
use Spatie\ModelStates\Tests\Dummy\AutoDetectStates\StateB;
use Spatie\ModelStates\Tests\Dummy\AutoDetectStates\AbstractState;

/**
 * @method static self first()
 * @method static self find(int $id)
 * @method static self create(array $data = [])
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
class PaymentWithAutoDetectState extends Model
{
    use HasStates;

    protected $table = 'payments';

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected function registerStates(): void
    {
        $this->addState('state', AbstractState::class);
            // ->allowTransition(StateA::class, StateB::class)
            // ->allowTransition(StateB::class, StateA::class);
    }
}
