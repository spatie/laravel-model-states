<?php

namespace Spatie\ModelStates\Tests\Dummy\AttributeState;

use Spatie\ModelStates\Transition;
use Spatie\ModelStates\TransitionContext;

class AttributeStateTransition extends Transition
{
    public static bool $transitioned = false;

    private TestModelWithAttributeState $model;

    public function __construct(TestModelWithAttributeState|TransitionContext $model)
    {
        self::$transitioned = false;

        if ($model instanceof TransitionContext) {
            $this->model = $model->model;
        } else {
            $this->model = $model;
        }
    }

    public function handle(): TestModelWithAttributeState
    {
        self::$transitioned = true;

        $this->model->state = new AttributeStateB($this->model);

        return $this->model;
    }
}
