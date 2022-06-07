<?php

namespace Spatie\ModelStates\Tests\Dummy\ModelStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\Tests\Dummy\ModelStates\AnotherDirectory\StateF;
use Spatie\ModelStates\Tests\Dummy\ModelStates\AnotherDirectory\StateG;
use Spatie\ModelStates\Tests\Dummy\ModelStates\AnotherDirectory\StateH;

abstract class ModelState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(StateA::class, StateB::class)
            ->allowTransition([StateA::class, StateB::class], StateC::class)
            ->allowTransition(StateA::class, StateD::class)
            ->allowTransition(StateA::class, StateF::class)
            ->registerState(StateF::class)
            ->registerState([StateG::class, StateH::class])
            ->default(StateA::class);
    }
}
