<?php

namespace Spatie\State\Tests\Dummy\States;

class Created extends PaymentState
{
    public function color(): string
    {
        return 'light-gray';
    }
}
