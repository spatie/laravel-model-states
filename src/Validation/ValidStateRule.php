<?php

namespace Spatie\ModelStates\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidStateRule implements ValidationRule
{
    private string $baseStateClass;

    private bool $nullable = false;

    public static function make(string $abstractStateClass): ValidStateRule
    {
        return new self($abstractStateClass);
    }

    public function __construct(string $abstractStateClass)
    {
        $this->baseStateClass = $abstractStateClass;
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

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->nullable && $value === null) {
            return;
        }

        $stateClass = $this->baseStateClass::resolveStateClass($value);

        if(! is_subclass_of($stateClass, $this->baseStateClass)) {
            $fail('This value is invalid');
        }
    }
}
