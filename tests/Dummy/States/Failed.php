<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

class Failed extends PaymentState
{
    public static $name = 'failed';

    public function color(): string
    {
        return 'red';
    }
}
