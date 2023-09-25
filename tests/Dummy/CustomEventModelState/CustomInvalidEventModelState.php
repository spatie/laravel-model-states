<?php

namespace Spatie\ModelStates\Tests\Dummy\CustomEventModelState;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class CustomInvalidEventModelState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(CustomInvalidEventModelStateA::class)
            ->allowTransition(CustomInvalidEventModelStateA::class, CustomInvalidEventModelStateB::class)
            ->stateChangedEvent(CustomInvalidStateChangedEvent::class);
    }
}
