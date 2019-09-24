<?php

namespace Spatie\State;

use Illuminate\Database\Eloquent\Model;
use Spatie\State\Exceptions\InvalidConfig;

class StateConfig
{
    /** @var string */
    public $field;

    /** @var string|\Spatie\State\State */
    public $stateClass;

    /** @var string[] */
    public $allowedTransitions = [];

    public function __construct(string $field, string $stateClass)
    {
        if (! is_subclass_of($stateClass, State::class)) {
            throw InvalidConfig::doesNotExtendState($stateClass);
        }

        $this->field = $field;

        $this->stateClass = $stateClass;
    }

    public function allowTransition(string $from, string $to, string $transition = null): StateConfig
    {
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

    /** TODO: add to documentation */
    public function allowTransitions(array $transitions): StateConfig
    {
        foreach($transitions as $transition) {
            $this->allowTransition($transition[0], $transition[1], $transition[2] ?? null);
        }

        return $this;
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return string|\Spatie\State\Transition|null
     */
    public function resolveTransition(Model $model, string $from, string $to)
    {
        $transitionKey = $this->createTransitionKey($from, $to);

        if (! array_key_exists($transitionKey, $this->allowedTransitions)) {
            return null;
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
