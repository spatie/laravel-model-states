<?php

namespace Spatie\ModelStates;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\Exceptions\InvalidConfig;

class StateConfig
{
    /** @var string */
    public $field;

    /** @var string|\Spatie\ModelStates\State */
    public $stateClass;

    /** @var string[] */
    public $allowedTransitions = [];

    /** @var string|null */
    public $defaultStateClass;

    public function __construct(string $field, string $stateClass)
    {
        if (! is_subclass_of($stateClass, State::class)) {
            throw InvalidConfig::doesNotExtendState($stateClass);
        }

        $this->field = $field;

        $this->stateClass = $stateClass;
    }

    public function default(string $defaultStateClass): StateConfig
    {
        $this->defaultStateClass = $defaultStateClass;

        return $this;
    }

    /**
     * @param string|array $from
     * @param string $to
     * @param string|null $transition
     *
     * @return \Spatie\ModelStates\StateConfig
     */
    public function allowTransition($from, string $to, string $transition = null): StateConfig
    {
        if (is_array($from)) {
            foreach ($from as $fromState) {
                $this->allowTransition($fromState, $to, $transition);
            }

            return $this;
        }

        if (! is_subclass_of($from, $this->stateClass)) {
            throw InvalidConfig::doesNotExtendBaseClass($from, $this->stateClass);
        }

        if (! is_subclass_of($to, $this->stateClass)) {
            throw InvalidConfig::doesNotExtendBaseClass($to, $this->stateClass);
        }

        if ($transition && ! is_subclass_of($transition, Transition::class)) {
            throw InvalidConfig::doesNotExtendTransition($transition);
        }

        $this->allowedTransitions[$this->createTransitionKey($from, $to)] = $transition;

        return $this;
    }

    public function allowTransitions(array $transitions): StateConfig
    {
        foreach ($transitions as $transition) {
            $this->allowTransition($transition[0], $transition[1], $transition[2] ?? null);
        }

        return $this;
    }

    /**
    * @param \Illuminate\Database\Eloquent\Model $model
    * @param string $fromClass
    */
    public function allowedTransitionsFrom(Model $model, string $fromClass)
    {
        $allowedTransitionsFrom = [];

        foreach ($this->allowedTransitions as $allowedTransition => $value) {
            list($from, $to) = explode('-', $allowedTransition);

            if ($from === $fromClass) {
                $allowedTransitionsFrom[] = get_class($this->stateClass::make($to, $model));
            }
        }

        return $allowedTransitionsFrom;
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return string|\Spatie\ModelStates\Transition|null
     */
    public function resolveTransition(Model $model, string $from, string $to)
    {
        $transitionKey = $this->createTransitionKey($from, $to);

        if (! array_key_exists($transitionKey, $this->allowedTransitions)) {
            return;
        }

        return $this->allowedTransitions[$transitionKey]
            ?? new DefaultTransition(
                $model,
                $this->field,
                $this->stateClass::make($to, $model)
            );
    }

    private function createTransitionKey(string $from, string $to): string
    {
        return "{$from}-{$to}";
    }
}
