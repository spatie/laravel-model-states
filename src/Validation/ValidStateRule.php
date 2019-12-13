<?php

namespace Spatie\ModelStates\Validation;

use Illuminate\Contracts\Validation\Rule;

class ValidStateRule implements Rule
{
    /** @var string|\Spatie\ModelStates\State */
    private $abstractStateClass;

    private bool $nullable = false;

    public static function make(string $abstractStateClass): ValidStateRule
    {
        return new self($abstractStateClass);
    }

    public function __construct(string $abstractStateClass)
    {
        $this->abstractStateClass = $abstractStateClass;
    }

    public function nullable(): ValidStateRule
    {
        $this->nullable = true;

        return $this;
    }

    public function required(): ValidStateRule
    {
        $this->nullable = false;

        return $this;
    }

    public function passes($attribute, $value): bool
    {
        if ($this->nullable && $value === null) {
            return true;
        }

        $stateClass = $this->abstractStateClass::resolveStateClass($value);

        return is_subclass_of($stateClass, $this->abstractStateClass);
    }

    public function message(): string
    {
        return 'This value is invalid';
    }
}
