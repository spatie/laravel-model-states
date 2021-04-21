<?php

namespace Spatie\ModelStates;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JsonSerializable;
use ReflectionClass;
use Spatie\ModelStates\Attributes\AttributeLoader;
use Spatie\ModelStates\Events\StateChanged;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use Spatie\ModelStates\Exceptions\InvalidConfig;

abstract class State implements Castable, JsonSerializable
{
    private Model $model;

    private StateConfig $stateConfig;

    private string $field;

    private static array $stateMapping = [];

    public function __construct(Model $model)
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
            $name = isset($stateClass::$name) ? (string) $stateClass::$name : null;

            if ($name == $state) {
                return $stateClass;
            }
        }

        return $state;
    }

    public static function make(string $name, Model $model): State
    {
        $stateClass = static::resolveStateClass($name);

        if (! is_subclass_of($stateClass, static::class)) {
            throw InvalidConfig::doesNotExtendBaseClass($name, static::class);
        }

        return new $stateClass($model);
    }

    public function getModel(): Model
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
        $model->{$this->field}->setField($this->field);

        event(new StateChanged(
            $this,
            $model->{$this->field},
            $transition,
            $this->model,
        ));

        return $model;
    }

    public function transitionableStates(): array
    {
        return $this->stateConfig->transitionableStates(static::getMorphClass());
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

    public function equals(...$otherStates): bool
    {
        foreach ($otherStates as $otherState) {
            $otherState = $this->resolveStateObject($otherState);

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
                $this->field,
                $newState
            );
        } else {
            $transitionArgs = [...$transitionArgs, $this->getField(), $newState];
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

        $stateConfig = static::config();

        foreach ($files as $file) {
            ['filename' => $className] = pathinfo($file);

            /** @var \Spatie\ModelStates\State|mixed $stateClass */
            $stateClass = $namespace . '\\' . $className;

            if (! is_subclass_of($stateClass, $stateConfig->baseStateClass)) {
                continue;
            }

            $resolvedStates[$stateClass::getMorphClass()] = $stateClass;
        }

        return $resolvedStates;
    }
}
