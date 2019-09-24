<?php

namespace Spatie\State\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Spatie\State\State;
use Spatie\State\Transition;

class StateChanged
{
    use SerializesModels;

    /**
     * @var State
     */
    public $initialState;

    /**
     * @var State
     */
    public $finalState;

    /**
     * @var Transition
     */
    public $transition;

    /**
     * @var Model
     */
    public $subject;

    /**
     * StateChanged constructor.
     *
     * @param State      $initialState
     * @param State      $finalState
     * @param Transition $transition
     * @param Model      $subject
     */
    public function __construct(
        State $initialState,
        State $finalState,
        Transition $transition,
        Model $subject
    ) {
        $this->initialState = $initialState;
        $this->finalState = $finalState;
        $this->transition = $transition;
        $this->subject = $subject;
    }
}
