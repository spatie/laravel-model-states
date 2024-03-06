<?php

namespace Spatie\ModelStates;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Support\Collection;
use Spatie\ModelStates\Exceptions\UnknownState;

class StateCaster implements CastsAttributes, SerializesCastableAttributes
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

        /** @var \Spatie\ModelStates\State $state */
        $state = new $stateClassName($model);

        $state->setField($key);

        return $state;
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

            if (! isset($mapping[$value])) {
                throw UnknownState::make(
                    $value,
                    $this->baseStateClass,
                    get_class($model),
                    $key
                );
            }

            $value = $mapping[$value];
        }

        if ($value instanceof $this->baseStateClass) {
            $value->setField($key);
        }

        return $value::getMorphClass();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     *
     * @return mixed
     */
    public function serialize($model, string $key, $value, array $attributes)
    {
        return $value instanceof State ? $value->jsonSerialize() : $value;
    }

    private function getStateMapping(): Collection
    {
        return $this->baseStateClass::getStateMapping();
    }
}
