<?php

namespace Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsStateWithNoRegisteredStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsState\StateA;

abstract class AllowAllTransitionsStateWithNoRegisteredStates extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(StateA::class)
            ->allowAllTransitions();
    }
}
