<?php

namespace Spatie\ModelStates;

use ReflectionClass;
use Spatie\ModelStates\Events\StateChanged;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\StructureDiscoverer\Discover;

class StateConfig
{
    /** @var class-string<\Spatie\ModelStates\State<\Illuminate\Database\Eloquent\Model>> */
    public string $baseStateClass;

    /** @var class-string<\Spatie\ModelStates\State<\Illuminate\Database\Eloquent\Model>>|null */
    public ?string $defaultStateClass = null;

    /** @var array<string, null|class-string<\Spatie\ModelStates\Transition>> */
    public array $allowedTransitions = [];

    /** @var string[] */
    public array $registeredStates = [];

    /** @var bool */
    public bool $shouldIgnoreSameState = false;

    public string $stateChangedEvent = StateChanged::class;

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

    public function ignoreSameState(): StateConfig
    {
        $this->shouldIgnoreSameState = true;

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
        if($this->shouldIgnoreSameState && $fromMorphClass === $toMorphClass){
            return true;
        }

        $transitionKey = $this->createTransitionKey($fromMorphClass, $toMorphClass);

        return array_key_exists($transitionKey, $this->allowedTransitions);
    }

    public function resolveTransitionClass(string $fromMorphClass, string $toMorphClass): ?string
    {
        $transitionKey = $this->createTransitionKey($fromMorphClass, $toMorphClass);

        if(array_key_exists($transitionKey, $this->allowedTransitions)) {
            return $this->allowedTransitions[$transitionKey];
        }

        return null;
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

    public function stateChangedEvent(string $event): StateConfig
    {
        $this->stateChangedEvent = $event;

        return $this;
    }

    /**
     * @throws InvalidConfig
     */
    public function allowAllTransitions(): StateConfig
    {
        $this->registerBaseStateClassDirectoryStates();

        if (empty($this->registeredStates)) {
            throw new InvalidConfig('No states registered for ' . $this->baseStateClass);
        }

        $this->allowTransitions(collect($this->registeredStates)->crossJoin($this->registeredStates)->toArray());

        return $this;
    }

    private function registerBaseStateClassDirectoryStates(): void
    {
        $reflector = new ReflectionClass($this->baseStateClass);
        $filename = $reflector->getFileName();
        $baseStateClassDirectory = dirname($filename);

        $stateClasses = Discover::in($baseStateClassDirectory)
                            ->classes()
                            ->extending($this->baseStateClass)
                            ->get();

        $this->registerState($stateClasses);
    }

    /**
     * Register all state classes from one or more custom directories.
     *
     * @param string ...$directories
     * @return $this
     */
    public function registerStatesFromDirectory(string ...$directories): StateConfig
    {
        $stateClasses = Discover::in(...$directories)
            ->classes()
            ->extending($this->baseStateClass)
            ->get();

        $this->registerState($stateClasses);

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
