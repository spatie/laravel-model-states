<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use Spatie\ModelStates\DefaultTransition;

class CustomDefaultTransition extends DefaultTransition
{
    /**
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function handle()
    {
        $originalState = $this->model->{$this->field} ? clone $this->model->{$this->field} : null;

        $this->model->{$this->field} = $this->newState;

        $this->model->saveQuietly();

        return $this->model;
    }
}
