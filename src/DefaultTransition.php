<?php

namespace Spatie\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\Tests\Dummy\DummyDependency;

class DefaultTransition extends Transition
{
    protected Model $model;

    protected string $field;

    protected State $newState;

    public function __construct(
        Model $model,
        string $field,
        State $newState
    ) {
        $this->model = $model;
        $this->field = $field;
        $this->newState = $newState;
    }

    public function handle(): Model
    {
        $this->model->{$this->field} = $this->newState;

        $this->model->save();

        return $this->model;
    }
}
