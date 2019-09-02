<?php

namespace Spatie\State\Tests\Dummy\States;

class Canceled extends PaymentState
{
    public function color(): string
    {
        return 'gray';
    }
}
