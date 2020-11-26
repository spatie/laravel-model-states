<?php

namespace Spatie\ModelStates\Tests\Dummy\AttributeState;

use Spatie\ModelStates\Transition;

class AttributeStateTransition extends Transition
{
    public static bool $transitioned = false;

    private TestModelWithAttributeState $model;

    public function __construct(TestModelWithAttributeState $model)
    {
        self::$transitioned = false;

        $this->model = $model;
    }

    public function handle(): TestModelWithAttributeState
    {
        self::$transitioned = true;

        $this->model->state = new AttributeStateB($this->model);

        return $this->model;
    }
}
