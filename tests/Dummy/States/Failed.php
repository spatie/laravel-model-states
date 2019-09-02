<?php

namespace Spatie\State\Tests\Dummy\States;

class Failed extends PaymentState
{
    public function color(): string
    {
        return 'red';
    }
}
