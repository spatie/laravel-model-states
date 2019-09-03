<?php

namespace Spatie\State\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\State\HasStates;
use Spatie\State\Tests\Dummy\States\Created;
use Spatie\State\Tests\Dummy\States\PaymentState;

/**
 * @property PaymentState state
 * @method static self first
 * @method static self create
 */
class Payment extends Model
{
    use HasStates;

    protected $states = [
        'state' => PaymentState::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Payment $payment) {
            $payment->state = new Created($payment);
        });
    }
}
