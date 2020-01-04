<?php

namespace Spatie\ModelStates;

use Illuminate\Database\Eloquent\Model;

class DefaultTransition extends Transition
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    /** @var string */
    protected $field;

    /** @var \Spatie\ModelStates\State */
    protected $newState;

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

        if ($this->newState->hasTimestamp()) {
            $this->model{$this->newState->getTimestamp()} = now();
        }

        $this->model->save();

        return $this->model;
    }
}
