<?php

namespace Spatie\ModelStates;

use Illuminate\Database\Eloquent\Model;

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

    public function handle()
    {
        $this->model->{$this->field} = $this->newState;

        $this->model->save();

        return $this->model;
    }
}
