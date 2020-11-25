<?php

namespace Spatie\ModelStates\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class UnknownState extends InvalidConfig implements ProvidesSolution
{
    protected string $field;

    protected string $expectedBaseStateClass;

    public static function make(
        string $givenStateClass,
        string $expectedBaseStateClass,
        string $modelClass,
        string $field
    ): self {
        return (new static("Unknown state `{$givenStateClass}` for `{$modelClass}::{$field}`, did you forget to list it in `{$expectedBaseStateClass}::config()`?"))
            ->setField($field)
            ->setExpectedBaseStateClass($expectedBaseStateClass);
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function setExpectedBaseStateClass(string $expectedBaseStateClass): self
    {
        $this->expectedBaseStateClass = $expectedBaseStateClass;

        return $this;
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('The state field is unknown')
            ->setSolutionDescription("Add the `{$this->field}` field to the `config` method inside `{$this->expectedBaseStateClass}`")
            ->setDocumentationLinks([
                'Configuring states' => 'https://docs.spatie.be/laravel-model-states/v1/working-with-states/01-configuring-states/',
            ]);
    }
}
