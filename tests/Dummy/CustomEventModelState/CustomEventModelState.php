<?php

namespace Spatie\ModelStates\Tests\Dummy\CustomEventModelState;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class CustomEventModelState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(CustomEventModelStateA::class)
            ->allowTransition(CustomEventModelStateA::class, CustomEventModelStateB::class)
            ->stateChangedEvent(CustomStateChangedEvent::class);
    }
}
