<?php

namespace Spatie\State\Tests\Dummy\States;

class Pending extends PaymentState
{
    public static $name = 'pending';

    public function color(): string
    {
        return 'orange';
    }
}
