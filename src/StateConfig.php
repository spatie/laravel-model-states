<?php

namespace Spatie\ModelStates;

use Spatie\ModelStates\Exceptions\InvalidConfig;

class StateConfig
{
    public string $modelClass;

    public string $fieldName;

    public string $baseStateClass;

    public ?string $defaultStateClass = null;

    /** @var string[] */
    public array $allowedTransitions = [];

    public function __construct(
        string $modelClass,
        string $fieldName,
        string $baseStateClass
    ) {
        $this->fieldName = $fieldName;
        $this->modelClass = $modelClass;
        $this->baseStateClass = $baseStateClass;
    }

    public function default(string $defaultStateClass): StateConfig
    {
        $this->defaultStateClass = $defaultStateClass;

        return $this;
    }

    public function allowTransition($from, string $to, string $transition = null): StateConfig
    {
        if (is_array($from)) {
            foreach ($from as $fromState) {
                $this->allowTransition($fromState, $to, $transition);
            }

            return $this;
        }

        if (! is_subclass_of($from, $this->baseStateClass)) {
            throw InvalidConfig::doesNotExtendBaseClass($from, $this->baseStateClass);
        }

        if (! is_subclass_of($to, $this->baseStateClass)) {
            throw InvalidConfig::doesNotExtendBaseClass($to, $this->baseStateClass);
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

    public function isTransitionAllowed(string $from, string $to): bool
    {
        $transitionKey = $this->createTransitionKey($from, $to);

        return array_key_exists($transitionKey, $this->allowedTransitions);
    }

    private function createTransitionKey(string $from, string $to): string
    {
        if (is_subclass_of($from, $this->baseStateClass)) {
            $from = $from::getMorphClass();
        }

        if (is_subclass_of($to, $this->baseStateClass)) {
            $to = $to::getMorphClass();
        }

        return "{$from}-{$to}";
    }
}
