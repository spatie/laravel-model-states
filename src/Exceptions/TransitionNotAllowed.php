<?php

namespace Spatie\ModelStates\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class TransitionNotAllowed extends CouldNotPerformTransition implements ProvidesSolution
{
    protected string $transitionClass;

    public static function make(string $modelClass, string $transitionClass): self
    {
        return (new static("The transition `{$transitionClass}` is not allowed on model `{$modelClass}` at the moment."))
            ->setTransitionClass($transitionClass);
    }

    public function setTransitionClass(string $transitionClass): self
    {
        $this->transitionClass = $transitionClass;

        return $this;
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('Transition not allowed')
            ->setSolutionDescription("Review your implementation of `canTransition` in {$this->transitionClass} if this is unexpected")
            ->setDocumentationLinks([
                'Custom transition classes' => 'https://docs.spatie.be/laravel-model-states/v1/working-with-transitions/02-custom-transition-classes/',
            ]);
    }
}
