<?php

namespace Spatie\ModelStates;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use ReflectionClass;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;

abstract class State implements Castable, JsonSerializable
{
    private static array $stateMapping = [];

    private Model $model;

    private StateConfig $stateConfig;

    public static function castUsing(array $arguments)
    {
        return new StateCaster(static::class);
    }

    public static function getMorphClass(): string
    {
        return static::$name ?? static::class;
    }

    public static function getStateMapping(): array
    {
        if (! isset(self::$stateMapping[static::class])) {
            self::$stateMapping[static::class] = static::resolveStateMapping();
        }

        return self::$stateMapping[static::class];
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
            $name = isset($stateClass::$name) ? (string) $stateClass::$name : null;

            if ($name == $state) {
                return $stateClass;
            }
        }

        return $state;
    }

    public function __construct(Model $model, StateConfig $stateConfig)
    {
        $this->model = $model;
        $this->stateConfig = $stateConfig;
    }

    public function transitionTo($newState, ...$transitionArgs): Model
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

    public function transition(Transition $transition): Model
    {
        if (method_exists($transition, 'canTransition')) {
            if (! $transition->canTransition()) {
                throw CouldNotPerformTransition::notAllowed($this->model, $transition);
            }
        }

        $model = app()->call([$transition, 'handle']);

        return $model;
    }

    public function transitionableStates(): array
    {
        return $this->stateConfig->transitionableStates(self::getMorphClass());
    }

    public function canTransitionTo($newState): bool
    {
        $newState = $this->resolveStateObject($newState);

        $from = static::getMorphClass();

        $to = $newState::getMorphClass();

        return $this->stateConfig->isTransitionAllowed($from, $to);
    }

    public function getValue(): string
    {
        return static::getMorphClass();
    }

    public function equals(State ...$otherStates): bool
    {
        foreach ($otherStates as $otherState) {
            if ($this->stateConfig->baseStateClass === $otherState->stateConfig->baseStateClass
                && $this->getValue() === $otherState->getValue()) {
                return true;
            }
        }

        return false;
    }

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

        if ($transitionClass === null) {
            $transition = new DefaultTransition(
                $this->model,
                $this->stateConfig->fieldName,
                $newState
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

        unset($files[0], $files[1]);

        $namespace = $reflection->getNamespaceName();

        $resolvedStates = [];

        foreach ($files as $file) {
            ['filename' => $className] = pathinfo($file);

            /** @var \Spatie\ModelStates\State|mixed $stateClass */
            $stateClass = $namespace . '\\' . $className;

            if (! is_subclass_of($stateClass, static::class)) {
                continue;
            }

            $resolvedStates[$stateClass::getMorphClass()] = $stateClass;
        }

        return $resolvedStates;
    }
}
