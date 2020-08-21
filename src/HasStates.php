<?php

namespace Spatie\ModelStates;

trait HasStates
{
    private array $stateCasts = [];

    /** @var \Spatie\ModelStates\StateConfig[] */
    private ?array $stateConfigs = null;

    abstract public function registerStates(): void;

    public function getCasts(): array
    {
        if ($this->stateConfigs === null) {
            $this->stateConfigs = [];
            $this->registerStates();
        }

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
    }
}
