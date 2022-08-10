<?php

namespace Spatie\ModelStates;

class TransitionContext {

    public $model;

    public string $field;

    public State $newState;

    public State $oldState;

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $field
     * @param  State  $newState
     */
    public function __construct($model, string $field, State $newState) {
        $this->model = $model;
        $this->field = $field;
        $this->newState = $newState;
        $this->oldState = $model->{$this->field};
    }
}