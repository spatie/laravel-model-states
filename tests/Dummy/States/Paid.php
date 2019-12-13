<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

class Paid extends PaymentState
{
    public static string $name = 'paid';

    public function color(): string
    {
        return 'green';
    }
}
