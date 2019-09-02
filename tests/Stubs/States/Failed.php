<?php

namespace Spatie\State\Tests\Stubs\States;

class Failed extends PaymentState
{
    public function color(): string
    {
        return 'red';
    }
}
