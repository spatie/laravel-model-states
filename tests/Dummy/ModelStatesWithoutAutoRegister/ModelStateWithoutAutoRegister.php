<?php

namespace Spatie\ModelStates\Tests\Dummy\ModelStatesWithoutAutoRegister;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\Tests\Dummy\ModelStatesWithoutAutoRegister\AnotherDirectory\StateF;


abstract class ModelStateWithoutAutoRegister extends State
{

    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(StateA::class, StateB::class)
            ->registerState(StateF::class)
            ->registerState([StateA::class, StateB::class])
            ->default(StateA::class)
            ->skipAutoRegisterStates();
    }

}
