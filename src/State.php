<?php

namespace Spatie\ModelStates;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;

abstract class State implements Castable
{
    private Model $model;

    private StateConfig $stateConfig;

    public static function castUsing()
    {
        return new StateCaster(static::class);
    }

    public static function getMorphClass(): string
    {
        return static::$name ?? static::class;
    }

    public function __construct(Model $model, StateConfig $stateConfig)
    {
        $this->model = $model;
        $this->stateConfig = $stateConfig;
    }

    public function transitionTo($newState): Model
    {
        $newState = $this->resolveState($newState);

        $from = static::getMorphClass();

        $to = $newState::getMorphClass();

        if (! $this->stateConfig->isTransitionAllowed($from, $to)) {
            throw CouldNotPerformTransition::notFound($from, $to, $this->model);
        }

        $transition = new DefaultTransition(
            $this->model,
            $this->stateConfig->fieldName,
            $newState
        );

        $model = app()->call([$transition, 'handle']);

        return $model;
    }

    private function resolveState($state): self
    {
        if (is_object($state) && is_subclass_of($state, $this->stateConfig->baseStateClass)) {
            return $state;
        }

        if (is_string($state) && is_subclass_of($state, $this->stateConfig->baseStateClass)) {
            return new $state($this->model, $this->stateConfig);
        }

        // TODO: via mapping
    }
}
