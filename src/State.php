<?php

namespace Spatie\State;

abstract class State
{
    /** @var static[] */
    public static $map = [];

    public function __toString(): string
    {
        $className = get_class($this);

        $alias = array_search($className, self::$map);

        return $alias ?? $className;
    }
}
