<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

class Canceled extends PaymentState
{
    public static string $name = 'canceled';

    public function color(): string
    {
        return 'gray';
    }
}
