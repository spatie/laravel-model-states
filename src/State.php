<?php

namespace Spatie\ModelStates;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Collection;
use JsonSerializable;
use ReflectionClass;
use Spatie\ModelStates\Attributes\AttributeLoader;
use Spatie\ModelStates\Events\StateChanged;
use Spatie\ModelStates\Exceptions\ClassDoesNotExtendBaseClass;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use Spatie\ModelStates\Exceptions\InvalidConfig;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
abstract class State implements Castable, JsonSerializable
{
    private $model;

    private StateConfig $stateConfig;

    private string $field;

    private static array $stateMapping = [];

    /**
     * @param  TModel  $model
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->stateConfig = static::config();
    }

    public static function config(): StateConfig
    {
        $reflection = new ReflectionClass(static::class);

        $baseClass = $reflection->name;

        while ($reflection && ! $reflection->isAbstract()) {
            $reflection = $reflection->getParentClass();

            $baseClass = $reflection->name;
        }

        $stateConfig = new StateConfig($baseClass);

        if (version_compare(PHP_VERSION, '8.0', '>=')) {
            $stateConfig = (new AttributeLoader($baseClass))->load($stateConfig);
        }

        return $stateConfig;
    }

    public static function castUsing(array $arguments)
    {
        return new StateCaster(static::class);
    }

    public static function getMorphClass(): string
    {
        return static::$name ?? static::class;
    }

    public static function getStateMapping(): Collection
    {
        if (! isset(self::$stateMapping[static::class])) {
            self::$stateMapping[static::class] = static::resolveStateMapping();
        }

        return collect(self::$stateMapping[static::class]);
    }

    public static function resolveStateClass($state): ?string
    {
        if ($state === null) {
            return null;
        }

        if ($state instanceof State) {
            return get_class($state);
        }

        foreach (static::getStateMapping() as $stateClass) {
            if (! class_exists($stateClass)) {
                continue;
            }

            // Loose comparison is needed here in order to support non-string values,
            // Laravel casts their database value automatically to strings if we didn't specify the fields in `$casts`.
            $name = $stateClass::getMorphClass();

            if ($name == $state) {
                return $stateClass;
            }
        }

        return $state;
    }

    /**
     * @param  string  $name
     * @param  TModel  $model
     * @return  State
     */
    public static function make(string $name, $model): State
    {
        $stateClass = static::resolveStateClass($name);

        if (! is_subclass_of($stateClass, static::class)) {
            throw InvalidConfig::doesNotExtendBaseClass($name, static::class);
        }

        return new $stateClass($model);
    }

    /**
     * @return TModel
     */
    public function getModel()
    {
        return $this->model;
    }

    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return \Illuminate\Support\Collection|string[]|static[] A list of class names.
     */
    public static function all(): Collection
    {
        return collect(self::resolveStateMapping());
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @param  string|State  $newState
     * @param  mixed  ...$transitionArgs
     * @return  TModel
     */
    public function transitionTo($newState, ...$transitionArgs)
    {
        $newState = $this->resolveStateObject($newState);

        $from = static::getMorphClass();

        $to = $newState::getMorphClass();

        if (! $this->stateConfig->isTransitionAllowed($from, $to)) {
            throw CouldNotPerformTransition::notFound($from, $to, $this->model);
        }

        $transition = $this->resolveTransitionClass(
            $from,
            $to,
            $newState,
            ...$transitionArgs
        );

        return $this->transition($transition);
    }

    /**
     * @param Transition $transition
     * @return  TModel
     * @throws ClassDoesNotExtendBaseClass
     */
    public function transition(Transition $transition)
    {
        if (method_exists($transition, 'canTransition')) {
            if (! $transition->canTransition()) {
                throw CouldNotPerformTransition::notAllowed($this->model, $transition);
            }
        }

        $model = app()->call([$transition, 'handle']);
        $model->{$this->field}->setField($this->field);

        $stateChangedEvent = $this->stateConfig->stateChangedEvent;

        if ($stateChangedEvent !== StateChanged::class && get_parent_class($stateChangedEvent) !== StateChanged::class) {
            throw ClassDoesNotExtendBaseClass::make($stateChangedEvent, StateChanged::class);
        }

        event(new $stateChangedEvent(
            $this,
            $model->{$this->field},
            $transition,
            $this->model,
            $this->field,
        ));

        return $model;
    }

    /**
     * Get an array of state names that can be transitioned to from the current state.
     *
     * @param  mixed  ...$transitionArgs  Optional arguments to pass to the transition
     * @return array  Array of state names as strings
     */
    public function transitionableStates(...$transitionArgs): array
    {
        return collect($this->stateConfig->transitionableStates(static::getMorphClass()))->reject(function ($state) use ($transitionArgs) {
            return ! $this->canTransitionTo($state, ...$transitionArgs);
        })->toArray();
    }

    /**
     * Get an array of instantiated state objects that can be transitioned to from the current state.
     *
     * @param  mixed  ...$transitionArgs  Optional arguments to pass to the transition
     * @return array  Array of state instances
     */
    public function transitionableStateInstances(...$transitionArgs): array
    {
        return collect($this->transitionableStates(...$transitionArgs))->map(function ($state) {
            $stateClass = $this::config()->baseStateClass::resolveStateClass($state);
            return (new $stateClass($this->getModel()));
        })->toArray();
    }

    /**
     * Get the count of states that can be transitioned to from the current state.
     *
     * @param  mixed  ...$transitionArgs  Optional arguments to pass to the transition logic.
     * @return int  The number of transitionable states.
     */
    public function transitionableStatesCount(...$transitionArgs): int
    {
        return count($this->transitionableStates(...$transitionArgs));
    }

    /**
     * Determine if there are any states that can be transitioned to from the current state.
     *
     * @param  mixed  ...$transitionArgs  Optional arguments to pass to the transition logic.
     * @return bool  True if there are available transitions; false otherwise.
     */
    public function hasTransitionableStates(...$transitionArgs): bool
    {
        return filled($this->transitionableStates(...$transitionArgs));
    }

    public function canTransitionTo($newState, ...$transitionArgs): bool
    {
        $newState = $this->resolveStateObject($newState);

        $from = static::getMorphClass();

        $to = $newState::getMorphClass();

        if (! $this->stateConfig->isTransitionAllowed($from, $to)) {
            return false;
        }

        $transition = $this->resolveTransitionClass(
            $from,
            $to,
            $newState,
            ...$transitionArgs
        );

        if (method_exists($transition, 'canTransition')) {
            return $transition->canTransition();
        }

        return true;
    }

    public function getValue(): string
    {
        return static::getMorphClass();
    }

    public function equals(...$otherStates): bool
    {
        foreach ($otherStates as $otherState) {
            $otherState = $this->resolveStateObject($otherState);

            if (
                $this->stateConfig->baseStateClass === $otherState->stateConfig->baseStateClass
                && $this->getValue() === $otherState->getValue()
            ) {
                return true;
            }
        }

        return false;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->getValue();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    private function resolveStateObject($state): self
    {
        if (is_object($state) && is_subclass_of($state, $this->stateConfig->baseStateClass)) {
            return $state;
        }

        $stateClassName = $this->stateConfig->baseStateClass::resolveStateClass($state);

        return new $stateClassName($this->model, $this->stateConfig);
    }

    private function resolveTransitionClass(
        string $from,
        string $to,
        State $newState,
        ...$transitionArgs
    ): Transition {
        $transitionClass = $this->stateConfig->resolveTransitionClass($from, $to);
        /**
         * @deprecated This behavior should be removed in the next major release.
         * Transitions will no longer need to be defined in the configuration file
         * as long as they extend the DefaultTransition class.
         */
        if ($transitionClass === null) {
            $defaultTransition = config('model-states.default_transition', DefaultTransition::class);

            $transition = new $defaultTransition(
                $this->model,
                $this->field,
                $newState,
                ...$transitionArgs
            );
        } elseif (is_subclass_of($transitionClass, DefaultTransition::class)) {
            $transition = new $transitionClass(
                $this->model,
                $this->field,
                $newState,
                ...$transitionArgs
            );
        } else {
            $transition = new $transitionClass($this->model, ...$transitionArgs);
        }

        return $transition;
    }

    private static function resolveStateMapping(): array
    {
        $reflection = new ReflectionClass(static::class);

        ['dirname' => $directory] = pathinfo($reflection->getFileName());

        $files = scandir($directory);

        $namespace = $reflection->getNamespaceName();

        $resolvedStates = [];

        $stateConfig = static::config();

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            ['filename' => $className] = pathinfo($file);

            /** @var \Spatie\ModelStates\State|mixed $stateClass */
            $stateClass = $namespace . '\\' . $className;

            if (! is_subclass_of($stateClass, $stateConfig->baseStateClass)) {
                continue;
            }

            $resolvedStates[$stateClass::getMorphClass()] = $stateClass;
        }

        foreach ($stateConfig->registeredStates as $stateClass) {
            $resolvedStates[$stateClass::getMorphClass()] = $stateClass;
        }

        return $resolvedStates;
    }
}
