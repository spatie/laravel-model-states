<?php

namespace Spatie\ModelStates\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class FieldDoesNotExtendState extends InvalidConfig implements ProvidesSolution
{
    protected string $field;

    protected string $expectedStateClass;

    protected string $actualClass;

    public static function make(string $field, string $expectedStateClass, string $actualClass): self
    {
        return (new static("State field `{$field}` expects state to be of type `{$expectedStateClass}`, instead got `{$actualClass}`"))
            ->setField($field)
            ->setExpectedStateClass($expectedStateClass)
            ->setActualClass($actualClass);
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function setExpectedStateClass(string $expectedStateClass): self
    {
        $this->expectedStateClass = $expectedStateClass;

        return $this;
    }

    public function setActualClass(string $actualClass): self
    {
        $this->actualClass = $actualClass;

        return $this;
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create("State field `{$this->field}` is the wrong type")
            ->setSolutionDescription("Make sure that states for state field `{$this->field}` extend `{$this->expectedStateClass}`, not `{$this->actualClass}`")
            ->setDocumentationLinks([
                'Configuring states' => 'https://docs.spatie.be/laravel-model-states/v1/working-with-states/01-configuring-states/',
            ]);
    }
}
