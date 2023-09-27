<?php

namespace Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsStateWithNoRegisteredStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class AllowAllTransitionsStateWithNoRegisteredStates extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(StateAWithNoRegisteredStates::class)
            ->allowAllTransitions();
    }
}
