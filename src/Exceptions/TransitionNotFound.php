<?php

namespace Spatie\ModelStates\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class TransitionNotFound extends CouldNotPerformTransition implements ProvidesSolution
{
    protected string $from;

    protected string $to;

    protected string $modelClass;

    public static function make(string $from, string $to, string $modelClass): self
    {
        return (new static("Transition from `{$from}` to `{$to}` on model `{$modelClass}` was not found, did you forget to register it in `{$modelClass}::registerStates()`?"))
            ->setFrom($from)
            ->setTo($to)
            ->setModelClass($modelClass);
    }

    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setModelClass(string $modelClass): self
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('Transition not found')
            ->setSolutionDescription("Register the transition in `{$this->modelClass}::registerStates()`")
            ->setDocumentationLinks([
                'Configuring transitions' => 'https://docs.spatie.be/laravel-model-states/v1/working-with-transitions/01-configuring-transitions/',
            ]);
    }
}
