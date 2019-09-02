<?php

namespace Spatie\State\Tests\Dummy\States;

class Paid extends PaymentState
{
    public function color(): string
    {
        return 'green';
    }
}
