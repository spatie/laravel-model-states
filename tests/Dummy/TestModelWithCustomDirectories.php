<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\Tests\Dummy\RegisterStatesFromCustomDirectories\RegisterStatesFromCustomDirectories;

class TestModelWithCustomDirectories extends TestModel
{
    protected $casts = [
        'state' => RegisterStatesFromCustomDirectories::class,
    ];
}
