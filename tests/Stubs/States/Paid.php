<?php

namespace Spatie\State\Tests\Stubs\States;

class Paid extends PaymentState
{
    public function color(): string
    {
        return 'green';
    }
}
