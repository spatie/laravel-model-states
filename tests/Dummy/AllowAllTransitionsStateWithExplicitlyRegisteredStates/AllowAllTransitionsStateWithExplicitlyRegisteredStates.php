<?php

namespace Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsStateWithExplicitlyRegisteredStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsStateWithNoRegisteredStates\StateAWithNoRegisteredStates;
use Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsStateWithNoRegisteredStates\StateBWithNoRegisteredStates;
use Spatie\ModelStates\Tests\Dummy\AllowAllTransitionsStateWithNoRegisteredStates\StateCWithNoRegisteredStates;

abstract class AllowAllTransitionsStateWithExplicitlyRegisteredStates extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->registerState(StateAWithNoRegisteredStates::class)
            ->registerState(StateBWithNoRegisteredStates::class)
            ->registerState(StateCWithNoRegisteredStates::class)
            ->default(StateAWithNoRegisteredStates::class)
            ->allowAllTransitions();
    }
}
