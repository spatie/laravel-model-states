<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use PHPUnit\Framework\Assert;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Tests\Dummy\TestModelWithCustomTransition;
use Spatie\ModelStates\Transition;
use Spatie\ModelStates\TransitionContext;

class CustomInvalidTransition extends Transition
{
    private TestModelWithCustomTransition $model;

    public function __construct($model, ...$transitionArgs)
    {
        if ($model instanceof TransitionContext) {
            $this->model = $model->model;
        } else {
            $this->model = $model;
        }
    }

    public function canTransition(): bool
    {
        Assert::assertInstanceOf(TestModelWithCustomTransition::class, $this->model);

        return false;
    }
}
