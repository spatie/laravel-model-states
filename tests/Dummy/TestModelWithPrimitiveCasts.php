<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Spatie\ModelStates\Tests\Dummy\ModelStates\ModelState;

class TestModelWithPrimitiveCasts extends TestModel
{
    protected $casts = [
        'state' => ModelState::class,
        'message' => 'boolean',
        'payload' => 'array',
        'amount' => 'decimal:2',
        'published_at' => 'datetime:Y-m-d',
    ];
}
