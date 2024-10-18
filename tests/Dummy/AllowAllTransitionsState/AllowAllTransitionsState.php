<?php

namespace Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsState;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class AllowAllTransitionsState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(StateA::class)
            ->allowAllTransitions();
    }
}
