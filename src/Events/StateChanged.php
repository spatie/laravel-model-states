<?php

namespace Spatie\ModelStates\Events;

use Spatie\ModelStates\State;
use Spatie\ModelStates\Transition;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class StateChanged
{
    use SerializesModels;

    /** @var \Spatie\ModelStates\State|null */
    public $initialState;

    /** @var \Spatie\ModelStates\State|null */
    public $finalState;

    /** @var \Spatie\ModelStates\Transition */
    public $transition;

    /** @var \Illuminate\Database\Eloquent\Model */
    public $model;

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
