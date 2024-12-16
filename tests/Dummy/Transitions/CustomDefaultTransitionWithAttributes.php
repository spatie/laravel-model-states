<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Spatie\ModelStates\DefaultTransition;
use Spatie\ModelStates\State;

class CustomDefaultTransitionWithAttributes extends DefaultTransition
{
    public function __construct($model, string $field, State $newState, public bool $silent = false)
    {
        parent::__construct($model, $field, $newState);
    }
}
