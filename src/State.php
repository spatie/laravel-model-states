<?php

namespace Spatie\State;

use Illuminate\Database\Eloquent\Model;
use Spatie\State\Exceptions\InvalidState;

abstract class State
{
    /** @var static[] */
    public static $map = [];

    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    public static function make(string $value, Model $model): State
    {
        $stateClass = isset(static::$map[$value])
            ? static::$map[$value]
            : $value;

        if (! is_subclass_of($stateClass, static::class)) {
            throw InvalidState::make($value, static::class);
        }

        return new $stateClass($model);
    }

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function __toString(): string
    {
        $className = get_class($this);

        $alias = array_search($className, self::$map);

        return $alias ?? $className;
    }
}
