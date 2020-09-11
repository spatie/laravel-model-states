<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Spatie\ModelStates\Tests\Dummy\TestModelWithCustomTransition;
use Spatie\ModelStates\Transition;

class CustomInvalidTransition extends Transition
{
    private TestModelWithCustomTransition $model;

    public function __construct(TestModelWithCustomTransition $model)
    {
        $this->model = $model;
    }

    public function canTransition(): bool
    {
        return false;
    }
}
