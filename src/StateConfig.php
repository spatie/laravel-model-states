<?php

namespace Spatie\ModelStates;

use Spatie\ModelStates\Exceptions\InvalidConfig;

class StateConfig
{
    /** @var string|\Spatie\ModelStates\State */
    public string $baseStateClass;

    /** @var string|null|\Spatie\ModelStates\State */
    public ?string $defaultStateClass = null;

    /** @var string[] */
    public array $allowedTransitions = [];

    /** @var string[] */
    public array $registeredStates = [];

    public function __construct(
        string $baseStateClass
    ) {
        $this->baseStateClass = $baseStateClass;
    }

    public function default(string $defaultStateClass): StateConfig
    {
        $this->defaultStateClass = $defaultStateClass;

        return $this;
    }

    public function allowTransition($from, string $to, ?string $transition = null): StateConfig
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

    public function isTransitionAllowed(string $fromMorphClass, string $toMorphClass): bool
    {
        $transitionKey = $this->createTransitionKey($fromMorphClass, $toMorphClass);

        return array_key_exists($transitionKey, $this->allowedTransitions);
    }

    public function resolveTransitionClass(string $fromMorphClass, string $toMorphClass): ?string
    {
        $transitionKey = $this->createTransitionKey($fromMorphClass, $toMorphClass);

        return $this->allowedTransitions[$transitionKey];
    }

    public function transitionableStates(string $fromMorphClass): array
    {
        $transitionableStates = [];

        foreach ($this->allowedTransitions as $allowedTransition => $value) {
            [$transitionFromMorphClass, $transitionToMorphClass] = explode('-', $allowedTransition);

            if ($transitionFromMorphClass !== $fromMorphClass) {
                continue;
            }

            $transitionableStates[] = $transitionToMorphClass;
        }

        return $transitionableStates;
    }

    public function registerState($stateClass): StateConfig
    {
        if (is_array($stateClass)) {
            foreach ($stateClass as $state) {
                $this->registerState($state);
            }

            return $this;
        }

        if (!is_subclass_of($stateClass, $this->baseStateClass)) {
            throw InvalidConfig::doesNotExtendBaseClass($stateClass, $this->baseStateClass);
        }

        $this->registeredStates[] = $stateClass;

        return $this;
    }

    /**
     * @param string|\Spatie\ModelStates\State $from
     * @param string|\Spatie\ModelStates\State $to
     *
     * @return string
     */
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
