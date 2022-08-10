<?php

namespace Spatie\ModelStates;

class DefaultTransition extends Transition
{
    protected $model;

    protected string $field;

    protected State $newState;

    /**
     * @param  TransitionContext  $model
     * @param  string  $field
     * @param  State  $newState
     */
    public function __construct($transitionContext, ...$transitionArgs) {
        $this->model = $transitionContext->model;
        $this->field = $transitionContext->field;
        $this->newState = $transitionContext->newState;
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
