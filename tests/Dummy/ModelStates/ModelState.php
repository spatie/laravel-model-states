<?php

namespace Spatie\ModelStates\Tests\Dummy\ModelStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ModelState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(StateA::class, StateB::class)
            ->allowTransition([StateA::class, StateB::class], StateC::class)
            ->allowTransition(StateA::class, StateD::class)
            ->default(StateA::class);
    }
}
