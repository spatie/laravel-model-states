<?php

namespace Spatie\State\Validation;

use Illuminate\Contracts\Validation\Rule;

class ValidStateRule implements Rule
{
    /** @var string|\Spatie\State\State */
    private $abstractStateClass;

    public function __construct(string $abstractStateClass)
    {
        $this->abstractStateClass = $abstractStateClass;
    }

    public function passes($attribute, $value): bool
    {
        $stateClass = $this->abstractStateClass::resolveStateClass($value);

        return is_subclass_of($stateClass, $this->abstractStateClass);
    }

    public function message(): string
    {
        return "This value is invalid";
    }
}
