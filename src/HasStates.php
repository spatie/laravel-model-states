<?php

namespace Spatie\ModelStates;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasStates
{
    private array $stateCasts = [];

    public static function bootHasStates(): void
    {
        self::creating(function ($model) {
            /**
             * @var \Spatie\ModelStates\HasStates $model
             */
            $model->setStateDefaults();
        });
    }

    public function initializeHasStates(): void
    {
        $this->setStateDefaults();
    }

    public static function getStates(): Collection
    {
        /** @var \Illuminate\Database\Eloquent\Model|\Spatie\ModelStates\HasStates $model */
        $model = new static();

        return collect($model->getStateConfigs())
            ->map(function (StateConfig $stateConfig) {
                return $stateConfig->baseStateClass::getStateMapping()->keys();
            });
    }

    public static function getDefaultStates(): Collection
    {
        /** @var \Illuminate\Database\Eloquent\Model|\Spatie\ModelStates\HasStates $model */
        $model = new static();

        return collect($model->getStateConfigs())
            ->map(function (StateConfig $stateConfig) {
                $defaultStateClass = $stateConfig->defaultStateClass;

                if ($defaultStateClass === null) {
                    return null;
                }

                return $defaultStateClass::getMorphClass();
            });
    }

    public static function getDefaultStateFor(string $fieldName): ?string
    {
        return static::getDefaultStates()[$fieldName] ?? null;
    }

    public static function getStatesFor(string $fieldName): Collection
    {
        return collect(static::getStates()[$fieldName] ?? []);
    }

    public function scopeWhereState(Builder $builder, string $column, $states): Builder
    {
        $states = Arr::wrap($states);

        $field = Str::afterLast($column, '.');

        return $builder->whereIn($column, $this->getStateNamesForQuery($field, $states));
    }

    public function scopeWhereNotState(Builder $builder, string $column, $states): Builder
    {
        $states = Arr::wrap($states);

        $field = Str::afterLast($column, '.');

        return $builder->whereNotIn($column, $this->getStateNamesForQuery($field, $states));
    }

    public function scopeOrWhereState(Builder $builder, string $column, $states): Builder
    {
        $states = Arr::wrap($states);

        $field = Str::afterLast($column, '.');

        return $builder->orWhereIn($column, $this->getStateNamesForQuery($field, $states));
    }

    public function scopeOrWhereNotState(Builder $builder, string $column, $states): Builder
    {
        $states = Arr::wrap($states);

        $field = Str::afterLast($column, '.');

        return $builder->orWhereNotIn($column, $this->getStateNamesForQuery($field, $states));
    }

    /**
     * @return array|\Spatie\ModelStates\StateConfig[]
     */
    private function getStateConfigs(): array
    {
        $casts = $this->getCasts();

        $states = [];

        foreach ($casts as $field => $state) {
            if (! is_subclass_of($state, State::class)) {
                continue;
            }

            /**
             * @var \Spatie\ModelStates\State $state
             * @var \Illuminate\Database\Eloquent\Model $this
             */
            $states[$field] = $state::config();
        }

        return $states;
    }

    private function getStateNamesForQuery(string $field, array $states): Collection
    {
        /** @var \Spatie\ModelStates\StateConfig|null $stateConfig */
        $stateConfig = $this->getStateConfigs()[$field];

        return $stateConfig->baseStateClass::getStateMapping()
            ->filter(function (string $className, string $morphName) use ($states) {
                return in_array($className, $states)
                    || in_array($morphName, $states);
            })
            ->keys();
    }

    private function setStateDefaults(): void
    {
        foreach ($this->getStateConfigs() as $field => $stateConfig) {
            if ($this->{$field} !== null) {
                continue;
            }

            if ($stateConfig->defaultStateClass === null) {
                continue;
            }

            $this->{$field} = $stateConfig->defaultStateClass;
        }
    }
}
