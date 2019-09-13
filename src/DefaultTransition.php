<?php

namespace Spatie\State;

use Illuminate\Database\Eloquent\Model;

class DefaultTransition extends Transition
{
    /** @var \Illuminate\Database\Eloquent\Model */
    private $model;

    /** @var string */
    private $field;

    /** @var \Spatie\State\State */
    private $newState;

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
