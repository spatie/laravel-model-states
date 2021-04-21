<?php

namespace Spatie\ModelStates\Tests\Dummy;

class TestModelUpdatingEvent
{
    public TestModel $model;

    public function __construct(TestModel $model)
    {
        $this->model = $model;
    }
}
