<?php

namespace Spatie\ModelStates\Tests\Dummy\AttributeState;

use Spatie\ModelStates\Attributes\AllowTransition;
use Spatie\ModelStates\Attributes\DefaultState;
use Spatie\ModelStates\Attributes\RegisterState;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AnotherDirectory\AttributeStateC;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AnotherDirectory\AttributeStateD;
use Spatie\ModelStates\Tests\Dummy\AttributeState\AnotherDirectory\AttributeStateE;

#[
    AllowTransition(AttributeStateA::class, AttributeStateB::class, AttributeStateTransition::class),
    AllowTransition(AttributeStateB::class, AttributeStateA::class),
    AllowTransition(AttributeStateA::class, AttributeStateC::class),
    DefaultState(AttributeStateA::class),
	RegisterState(AttributeStateC::class),
	RegisterState([AttributeStateD::class, AttributeStateE::class]),
]
abstract class AttributeState extends State
{
}
