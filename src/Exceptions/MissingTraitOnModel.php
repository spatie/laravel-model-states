<?php

namespace Spatie\ModelStates\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class MissingTraitOnModel extends InvalidConfig implements ProvidesSolution
{
    protected string $modelClass;

    protected string $trait;

    public static function make(string $modelClass, string $trait): self
    {
        return (new static("The method `resolveTransition` was not found on model `{$modelClass}`, are you sure it uses the `{$trait} trait?`"))
            ->setModelClass($modelClass)
            ->setTrait($trait);
    }

    public function setModelClass(string $modelClass): self
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    public function setTrait(string $trait): self
    {
        $this->trait = $trait;

        return $this;
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('Missing trait on model')
            ->setSolutionDescription("Use the `{$this->trait}` trait on `{$this->modelClass}`")
            ->setDocumentationLinks([
                'Configuring states' => 'https://docs.spatie.be/laravel-model-states/v1/working-with-states/01-configuring-states/',
            ]);
    }
}
