<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

class PaidWithCode extends PaymentState
{
    public static $name = 1;

    public function color(): string
    {
        return 'green';
    }
}
