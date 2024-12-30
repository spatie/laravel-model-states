<?php

namespace Spatie\ModelStates\Tests\Dummy\IgnoreSameStateModelState;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class IgnoreSameStateModelState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->ignoreSameState()
            ->allowTransition(IgnoreSameStateModelStateA::class, IgnoreSameStateModelStateB::class)
            ->default(IgnoreSameStateModelStateA::class);
    }
}
