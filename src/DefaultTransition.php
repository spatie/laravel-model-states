<?php

namespace Spatie\ModelStates;

class DefaultTransition extends Transition
{
    protected $model;

    protected string $field;

    protected State $newState;

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $field
     * @param  State  $newState
     */
    public function __construct(
        $model,
        string $field,
        State $newState
    ) {
        $this->model = $model;
        $this->field = $field;
        $this->newState = $newState;
    }

    /**
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function handle()
    {
        $originalState = $this->model->{$this->field} ? clone $this->model->{$this->field} : null;

        $this->model->{$this->field} = $this->newState;

        $this->model->save();

        return $this->model;
    }
}
