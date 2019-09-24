<?php

namespace Spatie\State\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Spatie\State\State;
use Spatie\State\Transition;

class StateChanged
{
    use SerializesModels;

    /** @var \Spatie\State\State */
    public $initialState;

    /** @var \Spatie\State\State */
    public $finalState;

    /** @var \Spatie\State\Transition */
    public $transition;

    /** @var \Illuminate\Database\Eloquent\Model */
    public $model;

    public function __construct(
        State $initialState,
        State $finalState,
        Transition $transition,
        Model $model
    ) {
        $this->initialState = $initialState;
        $this->finalState = $finalState;
        $this->transition = $transition;
        $this->model = $model;
    }
}
