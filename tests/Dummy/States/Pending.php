<?php

namespace Spatie\State\Tests\Dummy\States;

class Pending extends PaymentState
{
    public function color(): string
    {
        return 'orange';
    }
}
