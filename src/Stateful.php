<?php

namespace Spatie\State;


/*
 * If we'd implement Stateful, we're not able to use the type system
 * to only allow objects from the class `PaymentState` to be set.
 */
interface Stateful
{
//    public function setState(State $state);

    public function getState(): State;
}
