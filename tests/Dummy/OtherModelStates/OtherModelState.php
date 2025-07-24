<?php

namespace Spatie\ModelStates\Tests\Dummy\OtherModelStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\Tests\Dummy\Transitions\CustomDefaultTransitionWithAttributes;
use Spatie\ModelStates\Tests\Dummy\Transitions\CustomInvalidTransition;
use Spatie\ModelStates\Tests\Dummy\Transitions\CustomTransition;

class OtherModelState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(StateX::class, StateY::class, CustomTransition::class)
            ->allowTransition(StateX::class, StateZ::class, CustomInvalidTransition::class)
            ->allowTransition(StateY::class, StateZ::class, CustomDefaultTransitionWithAttributes::class);
    }
}
