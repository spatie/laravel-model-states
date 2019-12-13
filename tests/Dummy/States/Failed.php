<?php

namespace Spatie\ModelStates\Tests\Dummy\States;

class Failed extends PaymentState
{
    public static string $name = 'failed';

    public function color(): string
    {
        return 'red';
    }
}
