<?php

namespace Spatie\State\Tests\Stubs\States;

class Canceled extends PaymentState
{
    public function color(): string
    {
        return 'gray';
    }
}
