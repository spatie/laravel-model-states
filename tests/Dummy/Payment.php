<?php

namespace Spatie\State\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\State\HasStates;
use Spatie\State\Tests\Dummy\States\Created;
use Spatie\State\Tests\Dummy\States\PaymentState;

/**
 * @method static self first
 * @method static self find(int $id)
 * @method static self create(array $data = [])
 * @property int id
 *
 * @property \Spatie\State\Tests\Dummy\States\PaymentState state
 */
class Payment extends Model
{
    use HasStates;

    protected $states = [
        'state' => PaymentState::class,
    ];

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Payment $payment) {
            $payment->state = $payment->state ?? new Created($payment);
        });
    }
}
