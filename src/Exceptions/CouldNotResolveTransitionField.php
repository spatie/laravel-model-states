<?php

namespace Spatie\ModelStates\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class CouldNotResolveTransitionField extends CouldNotPerformTransition implements ProvidesSolution
{
    protected string $modelClass;

    public static function make(string $modelClass): self
    {
        return (new static("You tried to invoke {$modelClass}::transitionTo() directly, though there are multiple state fields configured."))
            ->setModelClass($modelClass);
    }

    public function setModelClass(string $modelClass): self
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('Could not resolve transition field')
            ->setSolutionDescription("Use {$this->modelClass}->stateField->transitionTo()")
            ->setDocumentationLinks([
                'Using transitions' => 'https://docs.spatie.be/laravel-model-states/v1/working-with-transitions/01-configuring-transitions/#using-transitions',
            ]);
    }
}
