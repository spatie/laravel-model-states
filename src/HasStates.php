<?php

namespace Spatie\ModelStates;

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
