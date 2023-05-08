<?php

namespace Spatie\ModelStates\Validation;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class ValidStateTransitionRule implements Rule
{
    private string $baseStateClass;

    private bool $nullable = false;

    private Model $model;

    private string $column;

    public function __construct(string $abstractStateClass, Model $model, string $column = 'state')
    {
        $this->baseStateClass = $abstractStateClass;
        $this->model = $model;
        $this->column = $column;
    }

    public static function make(string $abstractStateClass, Model $model, string $column = 'state'): ValidStateTransitionRule
    {
        return new self($abstractStateClass, $model, $column);
    }

    public function nullable(): ValidStateTransitionRule
    {
        $this->nullable = true;

        return $this;
    }

    public function required(): ValidStateTransitionRule
    {
        $this->nullable = false;

        return $this;
    }

    public function passes($attribute, $value): bool
    {
        if ($this->nullable && $value === null) {
            return true;
        }
        $stateClass = $this->baseStateClass::resolveStateClass($value);

        return is_subclass_of($stateClass, $this->baseStateClass) && $this->model->{$this->column} && $this->model->{$this->column}->canTransitionTo($stateClass);
    }

    public function message(): string
    {
        if ($this->model->{$this->column} && ($transitionable = $this->model->{$this->column}->transitionableStates())) {

            return trans('model-states::transition_not_allowed_with_allowed', [
                'transitions' => implode(', ', $transitionable),
            ]);
        } else {
            return trans('model-states::transition_not_allowed');
        }
    }
}
