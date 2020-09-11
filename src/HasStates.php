<?php

namespace Spatie\ModelStates;

use Illuminate\Support\Collection;

trait HasStates
{
    private array $stateCasts = [];

    /** @var \Spatie\ModelStates\StateConfig[] */
    private ?array $stateConfigs = null;

    abstract public function registerStates(): void;

    public static function bootHasStates()
    {
        self::creating(function ($model) {
            $model->initStateConfigs();

            /** @var \Spatie\ModelStates\StateConfig $stateConfig */
            foreach ($model->stateConfigs as $stateConfig) {
                if ($stateConfig->defaultStateClass === null) {
                    continue;
                }

                if ($model->{$stateConfig->fieldName} !== null) {
                    continue;
                }

                $model->{$stateConfig->fieldName} = $stateConfig->defaultStateClass;
            }
        });
    }

    public static function getStates(): Collection
    {
        $model = new static();

        $model->initStateConfigs();

        return collect($model->getStateConfigs())
            ->map(function (StateConfig $stateConfig) {
                return array_keys($stateConfig->baseStateClass::getStateMapping());
            });
    }

    public static function getStatesFor(string $fieldName): Collection
    {
        return collect(static::getStates()[$fieldName] ?? []);
    }

    public static function getDefaultStates(): Collection
    {
        $model = new static();

        $model->initStateConfigs();

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

    public function getCasts(): array
    {
        $this->initStateConfigs();

        return array_merge(
            parent::getCasts(),
            $this->stateCasts,
        );
    }

    protected function addState(string $fieldName, string $stateClass): StateConfig
    {
        $stateConfig = new StateConfig(
            static::class,
            $fieldName,
            $stateClass,
        );

        $this->stateCasts[$fieldName] = $stateClass;

        $this->stateConfigs[$fieldName] = $stateConfig;

        return $stateConfig;
    }

    public function getStateConfig(string $fieldName): StateConfig
    {
        return $this->stateConfigs[$fieldName];
    }

    /**
     * @return \Spatie\ModelStates\StateConfig[]
     */
    public function getStateConfigs(): array
    {
        return $this->stateConfigs ?? [];
    }

    private function initStateConfigs(): void
    {
        if ($this->stateConfigs === null) {
            $this->stateConfigs = [];
            $this->registerStates();
        }
    }
}
