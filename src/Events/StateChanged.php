<?php

namespace Spatie\ModelStates\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Transition;

class StateChanged
{
    use SerializesModels;

    public ?State $initialState;

    public ?State $finalState;

    public Transition $transition;

    public Model $model;

    public function __construct(
        ?State $initialState,
        ?State $finalState,
        Transition $transition,
        Model $model
    ) {
        $this->initialState = $initialState;
        $this->finalState = $finalState;
        $this->transition = $transition;
        $this->model = $model;
    }
}
