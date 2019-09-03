<?php

namespace Spatie\State\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\State\HasStates;
use Spatie\State\Tests\Dummy\States\Created;
use Spatie\State\Tests\Dummy\States\PaymentState;

/**
 * @method static self first
 * @method static self create
 */
class Payment extends Model
{
    use HasStates;

    /** @var \Spatie\State\Tests\Dummy\States\PaymentState */
    public $state;

    protected static function boot()
    {
        self::creating(function (Payment $payment) {
            $payment->state = new Created($payment);
        });

        parent::boot();
    }
}
