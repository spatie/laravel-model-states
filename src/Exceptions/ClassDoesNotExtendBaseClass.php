<?php

namespace Spatie\ModelStates\Exceptions;

use Illuminate\Support\Str;
use Facade\IgnitionContracts\Solution;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;

class ClassDoesNotExtendBaseClass extends InvalidConfig implements ProvidesSolution
{
    /** @var string */
    protected $class;

    /** @var string */
    protected $baseClass;

    public static function make(string $class, string $baseClass): self
    {
        return (new static("Class {$class} does not extend the `{$baseClass}` base class."))
            ->setClass($class)
            ->setBaseClass($baseClass);
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function setBaseClass(string $baseClass): self
    {
        $this->baseClass = $baseClass;

        return $this;
    }

    public function getSolution(): Solution
    {
        $documentationLinks = Str::endsWith($this->baseClass, 'State')
            ? ['Configuring states' => 'https://docs.spatie.be/laravel-model-states/v1/working-with-states/01-configuring-states/']
            : ['Custom transition classes' => 'https://docs.spatie.be/laravel-model-states/v1/working-with-transitions/02-custom-transition-classes/'];

        return BaseSolution::create('')
            ->setSolutionDescription("Make sure that `{$this->class}` extends `{$this->baseClass}`")
            ->setDocumentationLinks($documentationLinks);
    }
}
