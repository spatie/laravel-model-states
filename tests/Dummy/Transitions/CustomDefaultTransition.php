<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\DefaultTransition;

class CustomDefaultTransition extends DefaultTransition
{
    public function handle(): Model
    {
        $originalState = $this->model->{$this->field} ? clone $this->model->{$this->field} : null;

        $this->model->{$this->field} = $this->newState;

        $this->model->saveQuietly();

        return $this->model;
    }
}
