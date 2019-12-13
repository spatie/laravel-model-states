<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

class Pending extends PaymentState
{
    public static string $name = 'pending';

    public function color(): string
    {
        return 'orange';
    }
}
