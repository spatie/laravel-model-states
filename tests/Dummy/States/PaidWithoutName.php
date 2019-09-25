<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

class PaidWithoutName extends PaymentState
{
    public function color(): string
    {
        return 'green';
    }
}
