<?php

namespace Spatie\ModelStates\Events;

use Illuminate\Queue\SerializesModels;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Transition;

class StateChanged
{
    use SerializesModels;

    public ?State $initialState;

    public ?State $finalState;

    public Transition $transition;

    public $model;

    public ?string $field;

    /**
     * @param  string|State|null  $initialState
     * @param  string|State|null  $finalState
     * @param  Transition  $finalState
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function __construct(
        ?State $initialState,
        ?State $finalState,
        Transition $transition,
        $model,
        string $field
    ) {
        $this->initialState = $initialState;
        $this->finalState = $finalState;
        $this->transition = $transition;
        $this->model = $model;
        $this->field = $field;
    }
}
