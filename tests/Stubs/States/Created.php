<?php

namespace Spatie\State\Tests\Stubs\States;

class Created extends PaymentState
{
    public function color(): string
    {
        return 'light-gray';
    }
}
