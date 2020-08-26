<?php

namespace Spatie\ModelStates\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class UnknownState extends InvalidConfig implements ProvidesSolution
{
    protected string $field;

    protected string $modelClass;

    public static function make(string $field, string $modelClass): self
    {
        return (new static("No state field found for {$modelClass}::{$field}, did you forget to provide a mapping in {$modelClass}::registerStates()?"))
            ->setField($field)
            ->setModelClass($modelClass);
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function setModelClass(string $modelClass): self
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('The state field is unknown')
            ->setSolutionDescription("Add the `{$this->field}` field to the `registerStates` method inside `{$this->modelClass}`")
            ->setDocumentationLinks([
                'Configuring states' => 'https://docs.spatie.be/laravel-model-states/v1/working-with-states/01-configuring-states/',
            ]);
    }
}
