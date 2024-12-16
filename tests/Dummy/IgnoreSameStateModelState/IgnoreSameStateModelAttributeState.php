<?php

namespace Spatie\ModelStates\Tests\Dummy\IgnoreSameStateModelState;

use Spatie\ModelStates\Attributes\AllowTransition;
use Spatie\ModelStates\Attributes\DefaultState;
use Spatie\ModelStates\Attributes\IgnoreSameState;
use Spatie\ModelStates\State;

#[
    DefaultState(IgnoreSameStateModelAttributeStateA::class),
    AllowTransition(IgnoreSameStateModelAttributeStateA::class, IgnoreSameStateModelAttributeStateB::class),
    IgnoreSameState
]
abstract class IgnoreSameStateModelAttributeState extends State
{
}
