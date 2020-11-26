<?php

namespace Spatie\ModelStates\Tests\Dummy\AttributeState;

use Spatie\ModelStates\Attributes\AllowTransition;
use Spatie\ModelStates\Attributes\DefaultState;
use Spatie\ModelStates\State;

#[
    AllowTransition(AttributeStateA::class, AttributeStateB::class, AttributeStateTransition::class),
    DefaultState(AttributeStateA::class),
]
abstract class AttributeState extends State
{

}
