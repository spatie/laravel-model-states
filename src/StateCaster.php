<?php

namespace Spatie\ModelStates;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;

class StateCaster implements CastsAttributes
{
    /** @var string|\Spatie\ModelStates\State */
    private string $baseStateClass;

    public function __construct(string $baseStateClass)
    {
        $this->baseStateClass = $baseStateClass;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        $mapping = $this->getStateMapping();

        $stateClassName = $mapping[$value];

        /** @var \Spatie\ModelStates\StateConfig $stateConfig */
        $stateConfig = $model->getStateConfig($key);

        return new $stateClassName(
            $model,
            $stateConfig
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param \Spatie\ModelStates\State|string $value
     * @param array $attributes
     *
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! is_subclass_of($value, $this->baseStateClass)) {
            $mapping = $this->getStateMapping();

            $value = $mapping[$value];
        }

        return $value::getMorphClass();
    }

    private function getStateMapping(): Collection
    {
        return $this->baseStateClass::getStateMapping();
    }
}
