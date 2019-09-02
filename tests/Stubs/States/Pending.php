<?php

namespace Spatie\State\Tests\Stubs\States;

class Pending extends PaymentState
{
    public function color(): string
    {
        return 'orange';
    }
}
