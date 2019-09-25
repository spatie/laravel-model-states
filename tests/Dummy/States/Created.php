<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

class Created extends PaymentState
{
    public static $name = 'created';

    public function color(): string
    {
        return 'light-gray';
    }
}
