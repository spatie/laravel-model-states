<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

class Refunded extends PaymentState
{
    public static $name = 'refunded';
    public static $timestamp = 'refunded_at';

    public function color(): string
    {
        return 'orange';
    }
}
