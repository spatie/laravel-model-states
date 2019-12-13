<?php

namespace Spatie\ModelStates\Tests\Dummy;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class ModelWithMultipleStates extends Model
{
    use HasStates;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->stateA = new DummyState($this);
        $this->stateB = new DummyState($this);
    }

    protected function registerStates(): void
    {
        $this->addState('stateA', AbstractDummyState::class)->allowTransition(DummyState::class, DummyState::class);
        $this->addState('stateB', AbstractDummyState::class)->allowTransition(DummyState::class, DummyState::class);
    }
}
