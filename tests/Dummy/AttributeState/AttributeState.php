<?php

namespace Spatie\ModelStates\Tests\Dummy\AttributeState;

use Spatie\ModelStates\Attributes\AllowTransition;
use Spatie\ModelStates\Attributes\DefaultState;
use Spatie\ModelStates\State;

#[
    AllowTransition(AttributeStateA::class, AttributeStateB::class, AttributeStateTransition::class),
    AllowTransition(AttributeStateB::class, AttributeStateA::class),
    DefaultState(AttributeStateA::class),
]
abstract class AttributeState extends State
{

}
