<?php

namespace Spatie\State;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\State\Exceptions\InvalidConfig;
use Spatie\State\Exceptions\CouldNotPerformTransition;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasStates
{
    /** @var \Spatie\State\StateConfig[]|null */
    protected static $stateFields = null;

    abstract protected function registerStates(): void;

    public static function bootHasStates(): void
    {
        /** @var \Spatie\State\State $expectedStateClass */
        $serialiseState = function (string $field, string $expectedStateClass) {
            return function (Model $model) use ($field, $expectedStateClass) {
                $value = $model->getAttribute($field);

                if ($value === null) {
                    return;
                }

                $stateClass = $expectedStateClass::resolveStateClass($value);

                if (! is_subclass_of($stateClass, $expectedStateClass)) {
                    throw InvalidConfig::fieldDoesNotExtendState($field, $expectedStateClass, $stateClass);
                }

                $model->setAttribute(
                    $field,
                    State::resolveStateName($value)
                );
            };
        };

        /** @var \Spatie\State\State $expectedStateClass */
        $unserialiseState = function (string $field, string $expectedStateClass) {
            return function (Model $model) use ($field, $expectedStateClass) {
                $stateClass = $expectedStateClass::resolveStateClass($model->getAttribute($field));

                $model->setAttribute(
                    $field,
                    $stateClass
                        ? new $stateClass($model)
                        : null
                );
            };
        };

        foreach (self::getStateConfig() as $stateConfig) {
            $field = $stateConfig->field;
            $expectedStateClass = $stateConfig->stateClass;

            static::retrieved($unserialiseState($field, $expectedStateClass));
            static::created($unserialiseState($field, $expectedStateClass));
            static::saved($unserialiseState($field, $expectedStateClass));

            static::updating($serialiseState($field, $expectedStateClass));
            static::creating($serialiseState($field, $expectedStateClass));
            static::saving($serialiseState($field, $expectedStateClass));
        }
    }

    public function scopeWhereState(Builder $builder, string $field, $states): Builder
    {
        self::getStateConfig();

        /** @var \Spatie\State\StateConfig|null $stateConfig */
        $stateConfig = self::getStateConfig()[$field] ?? null;

        if (! $stateConfig) {
            throw InvalidConfig::unknownState($field, $this);
        }

        $abstractStateClass = $stateConfig->stateClass;

        $stateNames = collect((array) $states)->map(function ($state) use ($abstractStateClass) {
            return $abstractStateClass::resolveStateName($state);
        });

        return $builder->whereIn($field, $stateNames);
    }

    public function scopeWhereNotState(Builder $builder, string $field, $states): Builder
    {
        /** @var \Spatie\State\StateConfig|null $stateConfig */
        $stateConfig = self::getStateConfig()[$field] ?? null;

        if (! $stateConfig) {
            throw InvalidConfig::unknownState($field, $this);
        }

        $stateNames = collect((array) $states)->map(function ($state) use ($stateConfig) {
            return $stateConfig->stateClass::resolveStateName($state);
        });

        return $builder->whereNotIn($field, $stateNames);
    }

    /**
     * @param string $fromClass
     * @param string $toClass
     *
     * @return \Spatie\State\Transition|string|null
     */
    public function resolveTransitionClass(string $fromClass, string $toClass)
    {
        foreach (static::getStateConfig() as $stateConfig) {
            $transitionClass = $stateConfig->resolveTransition($this, $fromClass, $toClass);

            if ($transitionClass) {
                return $transitionClass;
            }
        }

        throw CouldNotPerformTransition::notFound($fromClass, $toClass, $this);
    }

    protected function addState(string $field, string $stateClass): StateConfig
    {
        $stateConfig = new StateConfig($field, $stateClass);

        static::$stateFields[$stateConfig->field] = $stateConfig;

        return $stateConfig;
    }

    /**
     * @return \Spatie\State\StateConfig[]
     */
    private static function getStateConfig(): array
    {
        if (static::$stateFields === null) {
            static::$stateFields = [];

            (new static)->registerStates();
        }

        return static::$stateFields ?? [];
    }
}
